param(
  [string]$ProjectPath = "C:\inetpub\wwwroot\smkmuh04byl-mischool",
  [string]$Domain = "smkmuh04byl.mischool.id"
)

$ErrorActionPreference = "Stop"

function Ensure-Line {
  param(
    [string]$FilePath,
    [string]$Key,
    [string]$Value
  )

  if (!(Test-Path $FilePath)) {
    New-Item -ItemType File -Path $FilePath -Force | Out-Null
  }

  $content = Get-Content -Path $FilePath -Raw
  $escapedKey = [Regex]::Escape($Key)
  $pattern = "(?m)^$escapedKey=.*$"
  $replacement = "$Key=$Value"

  if ($content -match $pattern) {
    $updated = [Regex]::Replace($content, $pattern, $replacement)
  } else {
    if ($content.Length -gt 0 -and -not $content.EndsWith("`n")) {
      $content += "`r`n"
    }
    $updated = $content + $replacement + "`r`n"
  }

  Set-Content -Path $FilePath -Value $updated -NoNewline
}

Write-Host "ProjectPath: $ProjectPath"
if (!(Test-Path $ProjectPath)) { throw "Project path not found: $ProjectPath" }
Set-Location $ProjectPath

# 1. Check native dependencies (PHP, MySQL, Redis)
Write-Host "Checking Native Dependencies (PHP, MySQL, Redis)..." -ForegroundColor Yellow

$phpPath = (Get-Command php.exe -ErrorAction SilentlyContinue).Source
if (-not $phpPath) {
    Write-Warning "PHP tidak terdeteksi di System PATH! Pastikan PHP sudah terinstall manual."
    exit
} else {
    Write-Host "PHP ditemukan di: $phpPath" -ForegroundColor Green
}

if (-not (Get-Service mysql* -ErrorAction SilentlyContinue)) {
    Write-Warning "Service MySQL tidak ditemukan!"
} else {
    Write-Host "MySQL Service berjalan." -ForegroundColor Green
}

if (-not (Get-Service redis* -ErrorAction SilentlyContinue) -and -not (Get-Command redis-server.exe -ErrorAction SilentlyContinue)) {
    Write-Warning "Redis tidak ditemukan. Pastikan Redis sudah diinstall jika digunakan di .env."
} else {
    Write-Host "Redis terdeteksi." -ForegroundColor Green
}

# Install NSSM via Chocolatey jika belum ada
$nssmPath = (Get-Command nssm -ErrorAction SilentlyContinue).Source
if (-not $nssmPath) {
    Write-Host "Installing NSSM via Chocolatey..." -ForegroundColor Yellow
    choco install nssm -y
}

# Check Composer
if (!(Get-Command composer -ErrorAction SilentlyContinue)) {
  Write-Host "Installing Composer..."
  & choco install -y composer
}

# Check Node.js
if (!(Get-Command node -ErrorAction SilentlyContinue)) {
  Write-Host "Installing Node.js..."
  & choco install -y nodejs
}

## 2. Setup .env
#if (!(Test-Path ".\.env")) {
#  if (Test-Path ".\.env.production") {
#    Copy-Item ".\.env.production" ".\.env" -Force
#  } else {
#    New-Item -ItemType File -Path ".\.env" -Force | Out-Null
#  }
#}

#Ensure-Line -FilePath ".\.env" -Key "APP_ENV" -Value "production"
#Ensure-Line -FilePath ".\.env" -Key "APP_DEBUG" -Value "false"
#Ensure-Line -FilePath ".\.env" -Key "APP_URL" -Value ("http://" + $Domain)
#Ensure-Line -FilePath ".\.env" -Key "DB_HOST" -Value "127.0.0.1"
#Ensure-Line -FilePath ".\.env" -Key "DB_PORT" -Value "3306"
#Ensure-Line -FilePath ".\.env" -Key "DB_CONNECTION" -Value "mysql"
#Ensure-Line -FilePath ".\.env" -Key "REDIS_HOST" -Value "127.0.0.1"
#Ensure-Line -FilePath ".\.env" -Key "REDIS_PORT" -Value "6379"

# 3. Install PHP dependencies
Write-Host "Installing PHP dependencies..."
& composer install --no-dev --optimize-autoloader --no-interaction

# 4. Build assets
#Write-Host "Building assets..."
#& npm install
#& npm run build

# 5. Generate key
& php artisan key:generate --force

# 6. Setup storage link
& php artisan storage:link

# 7. Setup permissions
Write-Host "Setting permissions..."
& icacls ".\storage" /grant Everyone:F /T
& icacls ".\bootstrap\cache" /grant Everyone:F /T

# 8. Start MySQL service
Write-Host "Starting MySQL service..."
Start-Service mysql80 -ErrorAction SilentlyContinue

# 9. Start Redis service
Write-Host "Starting Redis service..."
Start-Service redis -ErrorAction SilentlyContinue

# 10. Wait for services
Start-Sleep -Seconds 10

# 11. Create database if not exists
#Write-Host "Setting up database..."
#& mysql -u root -e "CREATE DATABASE IF NOT EXISTS mischool;"
#& mysql -u root -e "CREATE USER IF NOT EXISTS 'mischool'@'localhost' IDENTIFIED BY 'mischoolpassword';"
#& mysql -u root -e "GRANT ALL PRIVILEGES ON mischool.* TO 'mischool'@'localhost';"
#& mysql -u root -e "FLUSH PRIVILEGES;"

# 12. Run migrations
Write-Host "Running migrations..."
& php artisan migrate --force

# 13. Cache configuration
Write-Host "Caching configuration..."
& php artisan config:cache
& php artisan route:cache
& php artisan view:cache

# 14. Setup IIS Load Balancer (ARR + 3 Workers)
Write-Host "Checking and Installing IIS + ARR Dependencies..."
if (!(Get-WindowsFeature -Name Web-Server -ErrorAction SilentlyContinue).Installed) {
    Write-Host "Installing IIS..."
    Install-WindowsFeature -Name Web-Server,Web-Mgmt-Tools,Web-Scripting-Tools,Web-Mgmt-Console -IncludeAllSubFeature -Verbose
}

Write-Host "Checking and Installing IIS URL Rewrite & ARR..."
if (!(Get-Command choco -ErrorAction SilentlyContinue)) {
    Write-Warning "Chocolatey is required to install IIS extensions."
} else {
    # Instal URL Rewrite
    Write-Host "Installing IIS URL Rewrite..."
    & choco install urlrewrite -y

    # Instal Application Request Routing (ARR)
    Write-Host "Installing IIS ARR..."
    & choco install iis-arr -y
}

Import-Module WebAdministration -ErrorAction SilentlyContinue

Write-Host "Setting up IIS and ARR Load Balancer..."

# Stop IIS default site (ignore errors if it doesn't exist)
if (Get-Website -Name "Default Web Site" -ErrorAction SilentlyContinue) {
    Stop-Website -Name "Default Web Site" -ErrorAction SilentlyContinue
}

# 14a. Setup 3 Worker Pools & Sites (ports 9001, 9002, 9003)
$workers = @(9001, 9002, 9003)
foreach ($port in $workers) {
  $poolName = "mischool_worker_$port"
  $siteName = "mischool_site_$port"

  # Create AppPool if not exists
  if (!(Test-Path "IIS:\AppPools\$poolName")) {
    New-WebAppPool -Name $poolName -Force
    Set-ItemProperty "IIS:\AppPools\$poolName" -Name managedRuntimeVersion -Value ""
    Set-ItemProperty "IIS:\AppPools\$poolName" -Name processModel.identityType -Value "ApplicationPoolIdentity"
  }

  # Create Site if not exists
  if (!(Test-Path "IIS:\Sites\$siteName")) {
    New-Website -Name $siteName -Port $port -IPAddress "*" -PhysicalPath "$ProjectPath\public" -ApplicationPool $poolName -Force
  }
}

# (Server Farm Creation moved to below to use Native PowerShell WebAdministration module)

# Deklarasi appcmd yang sempat terhapus
$appcmd = "$env:systemroot\system32\inetsrv\appcmd.exe"

# 14c. Create Main Load Balancer Website (Port 8080)
$mainSite = "smkmuh04byl-mischool"
$lbPool = "mischool_lb"
$lbPath = "$ProjectPath\lb"

if (!(Test-Path $lbPath)) { New-Item -ItemType Directory -Path $lbPath -Force | Out-Null }

if (!(Test-Path "IIS:\Sites\$mainSite")) {
  # Main App Pool
  if (!(Test-Path "IIS:\AppPools\$lbPool")) {
    New-WebAppPool -Name $lbPool -Force
    Set-ItemProperty "IIS:\AppPools\$lbPool" -Name managedRuntimeVersion -Value ""
  }

  New-Website -Name $mainSite -Port 8080 -IPAddress "*" -HostHeader $Domain -PhysicalPath $lbPath -ApplicationPool $lbPool -Force
}

# Ensure the main site is started
& $appcmd start site "$mainSite" 2>&1 | Out-Null

# Create web.config (for Main Load Balancer Site)
@'
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
  <system.webServer>
    <rewrite>
      <rules>
        <rule name="RouteToFarm" stopProcessing="true">
          <match url=".*" />
          <action type="Rewrite" url="http://mischool_farm/{R:0}" appendQueryString="true" />
        </rule>
      </rules>
    </rewrite>
    <security>
      <requestFiltering>
        <requestLimits maxAllowedContentLength="104857600" />
      </requestFiltering>
    </security>
  </system.webServer>
</configuration>
'@ | Out-File -FilePath "$ProjectPath\public\web.config" -Encoding UTF8

# Create web.config for Worker Sites
$workerWebConfig = @'
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Laravel Force" stopProcessing="true">
                    <match url="^(.*)$" ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" appendQueryString="true" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
'@

# Save worker web.config (Assuming workers serve from the same physical path,
# the main LB config in \public will override.
# In a true ARR setup, the LB site should point to an empty dir with its own web.config,
# while workers point to the Laravel \public dir.
# I will adjust the physical paths for clarity.)

# Update LB Physical Path to avoid conflict with workers
$lbPath = "$ProjectPath\lb"
if (!(Test-Path $lbPath)) { New-Item -ItemType Directory -Path $lbPath -Force }

# 14b. Bypassing ARR Farm - Setup Pure PHP-Based Round Robin Load Balancer
$farmName = "mischool_farm"

# Pastikan appcmd tersedia
$appcmd = "$env:systemroot\system32\inetsrv\appcmd.exe"

# Import IIS provider for PowerShell
Import-Module WebAdministration -ErrorAction SilentlyContinue

# Hapus Farm lama jika ada menggunakan appcmd
& $appcmd set config -section:webFarms /-"[name='$farmName']" /commit:apphost 2>&1 | Out-Null

# 1. Bersihkan folder LB jika ada file lama
Remove-Item "$lbPath\*" -Recurse -Force -ErrorAction SilentlyContinue

# 2. Buat skrip PHP Load Balancer (proxy.php / index.php)
$proxyPhp = @'
<?php
// Daftar Worker
$workers = ['http://127.0.0.1:9001', 'http://127.0.0.1:9002', 'http://127.0.0.1:9003'];

// Ambil giliran dari file teks sederhana (Round Robin)
$counterFile = __DIR__ . '/counter.txt';
if (!file_exists($counterFile)) file_put_contents($counterFile, '0');

// Lock file untuk menghindari race condition
$fp = fopen($counterFile, 'c+');
flock($fp, LOCK_EX);
$current = (int)fread($fp, filesize($counterFile) ?: 1);
$next = ($current + 1) % count($workers);
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, (string)$next);
flock($fp, LOCK_UN);
fclose($fp);

// Pilih Worker
$targetServer = $workers[$current];
$requestUri = $_SERVER['REQUEST_URI'];

// Redirect ke Worker (menggunakan 307 Temporary Redirect agar method POST/GET tetap terjaga)
header("Location: $targetServer$requestUri", true, 307);
exit;
?>
'@
$proxyPhp | Out-File -FilePath "$lbPath\index.php" -Encoding UTF8

# 3. Buat web.config sederhana untuk merutekan SEMUA request ke index.php tersebut
$lbWebConfig = @'
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="PHP Proxy" stopProcessing="true">
                    <match url=".*" />
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
'@
$lbWebConfig | Out-File -FilePath "$lbPath\web.config" -Encoding UTF8

# 4. Berikan izin baca-tulis agar PHP bisa menyimpan giliran di counter.txt
icacls "$lbPath" /grant "IIS_IUSRS:(OI)(CI)F" /T | Out-Null

# Write Worker web.config
$workerWebConfig | Out-File -FilePath "$ProjectPath\public\web.config" -Encoding UTF8

# Update Main Site to point to LB Path
Set-ItemProperty -Path "IIS:\Sites\smkmuh04byl-mischool" -Name "physicalPath" -Value $lbPath

# Restart IIS
& iisreset /restart

# 15. Setup Apache Reverse Proxy (if Apache exists)
Write-Host "Checking for Apache to configure Reverse Proxy..."

# Coba cari dari proses yang jalan, kalau gak ketemu cek folder C:\Apache24
$apacheDir = $null
$apacheProcess = Get-Process httpd -ErrorAction SilentlyContinue | Select-Object -First 1
if ($apacheProcess -and $apacheProcess.Path) {
    $apacheDir = Split-Path (Split-Path $apacheProcess.Path -Parent) -Parent
} elseif (Test-Path "C:\Apache24\bin\httpd.exe") {
    $apacheDir = "C:\Apache24"
}

if ($apacheDir) {
    $httpdConf = "$apacheDir\conf\httpd.conf"
    $vhostsConf = "$apacheDir\conf\extra\httpd-vhosts.conf"

    if (Test-Path $httpdConf) {
        Write-Host "Found Apache at $apacheDir. Configuring proxy modules..."

        # Enable proxy modules
        $confContent = Get-Content $httpdConf -Raw
        $confContent = $confContent -replace '(?m)^#LoadModule proxy_module modules/mod_proxy.so', 'LoadModule proxy_module modules/mod_proxy.so'
        $confContent = $confContent -replace '(?m)^#LoadModule proxy_http_module modules/mod_proxy_http.so', 'LoadModule proxy_http_module modules/mod_proxy_http.so'

        # Ensure vhosts is included
        $confContent = $confContent -replace '(?m)^#Include conf/extra/httpd-vhosts.conf', 'Include conf/extra/httpd-vhosts.conf'
        Set-Content -Path $httpdConf -Value $confContent -NoNewline

        # Setup VirtualHost
        if (!(Test-Path $vhostsConf)) {
            New-Item -ItemType File -Path $vhostsConf -Force | Out-Null
        }

        Write-Host "Configuring VirtualHost in $vhostsConf..."
        $vhostConfig = @"

<VirtualHost *:80>
    ServerName $Domain
    ProxyPreserveHost On
    ProxyPass / http://127.0.0.1:8080/
    ProxyPassReverse / http://127.0.0.1:8080/
</VirtualHost>
"@
        $vhostContent = Get-Content $vhostsConf -Raw
        if ($vhostContent -notmatch "ServerName $Domain") {
            Add-Content -Path $vhostsConf -Value $vhostConfig
        }

        Write-Host "Restarting Apache..."
        # Coba restart via Service dulu
        $apacheServiceName = (Get-WmiObject -Class Win32_Service | Where-Object { $_.PathName -match 'httpd.exe' }).Name
        if ($apacheServiceName) {
            Restart-Service -Name $apacheServiceName -Force -ErrorAction SilentlyContinue
        } else {
            # Kalau bukan service, matikan prosesnya lalu nyalakan manual
            Stop-Process -Name httpd -Force -ErrorAction SilentlyContinue
            Start-Sleep -Seconds 2
            Start-Process "$apacheDir\bin\httpd.exe" -WindowStyle Hidden
        }
    }
} else {
    Write-Host "Apache not detected. Skipping reverse proxy setup."
}

# 16. Konfigurasi Laravel Background Jobs (Queue & Scheduler)
Write-Host "Mengonfigurasi Laravel Queue Worker dan Scheduler..." -ForegroundColor Cyan

# Menyiapkan variabel
$phpPath = (Get-Command php -ErrorAction SilentlyContinue).Source
if (-not $phpPath) { $phpPath = "php" }
$workerDir = $ProjectPath

# 16a. Konfigurasi Task Scheduler untuk Laravel Cron (Setiap 1 menit)
$action = New-ScheduledTaskAction -Execute "$phpPath" -Argument "$workerDir\artisan schedule:run" -WorkingDirectory $workerDir
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1)
$principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -LogonType ServiceAccount -RunLevel Highest
$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable

# Hapus task lama jika ada, lalu buat baru
Unregister-ScheduledTask -TaskName "LaravelScheduler_Mischool" -Confirm:$false -ErrorAction SilentlyContinue
Register-ScheduledTask -TaskName "LaravelScheduler_Mischool" -Action $action -Trigger $trigger -Principal $principal -Settings $settings -Description "Laravel Scheduler untuk Mischool" | Out-Null
Write-Host "Task Scheduler untuk Laravel Cron berhasil dibuat." -ForegroundColor Green

# 16b. Konfigurasi Queue Worker sebagai Windows Service menggunakan NSSM
$nssmPath = (Get-Command nssm -ErrorAction SilentlyContinue).Source
if (-not $nssmPath) {
    Write-Host "NSSM belum terinstall. Menginstall NSSM via Chocolatey..." -ForegroundColor Yellow
    & choco install nssm -y
    $nssmPath = (Get-Command nssm -ErrorAction SilentlyContinue).Source
}

if ($nssmPath) {
    $baseServiceName = "MischoolQueueWorker"
    $numberOfWorkers = 3 # Jumlah worker yang akan dijalankan paralel

    Write-Host "Membuat $numberOfWorkers Laravel Queue Worker secara paralel..." -ForegroundColor Cyan

    for ($i = 1; $i -le $numberOfWorkers; $i++) {
        $serviceName = "${baseServiceName}_${i}"

        # Hapus service lama jika ada
        if (Get-Service $serviceName -ErrorAction SilentlyContinue) {
            Stop-Service $serviceName -Force -ErrorAction SilentlyContinue
            & $nssmPath remove $serviceName confirm
            Start-Sleep -Seconds 2
        }

        # Install Queue Worker sebagai service
        & $nssmPath install $serviceName "$phpPath" "$workerDir\artisan queue:work --tries=3 --max-time=3600"
        & $nssmPath set $serviceName AppDirectory "$workerDir"
        & $nssmPath set $serviceName AppStdout "$workerDir\storage\logs\queue-worker-${i}.log"
        & $nssmPath set $serviceName AppStderr "$workerDir\storage\logs\queue-worker-${i}-error.log"
        & $nssmPath set $serviceName AppEnvironmentExtra "APP_ENV=production"

        # Mulai service
        Start-Service $serviceName
        Set-Service $serviceName -StartupType Automatic
        Write-Host "Worker $i berhasil dijalankan sebagai Windows Service ($serviceName)." -ForegroundColor Green
    }
} else {
    Write-Warning "Gagal menemukan/menginstall NSSM. Queue Worker harus dijalankan manual."
}

# 17. Final check
Write-Host "=== Deployment Complete ==="
Write-Host "Access your application at: http://$Domain:8080"
Write-Host "Note: HTTPS and standard Port 80 must be handled via Apache Reverse Proxy if Apache is running on port 80."
Write-Host ""
Write-Host "Services status:"
Get-Service mysql, redis -ErrorAction SilentlyContinue | Format-Table Name, Status, StartType -AutoSize
Write-Host ""
Write-Host "IIS Sites:"
& $appcmd list site
