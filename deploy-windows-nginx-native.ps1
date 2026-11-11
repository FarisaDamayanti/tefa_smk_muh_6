param(
    [string]$Domain = "smkmuh04byl-mischool",
    [string]$ProjectPath = "C:\inetpub\wwwroot\smkmuh04byl-mischool",
    [string]$PhpVersion = "8.2",
    [int]$AppPort = 8080
)

$ErrorActionPreference = "Stop"

Write-Host "=========================================" -ForegroundColor Cyan
Write-Host "Memulai Deployment Mischool (IIS Workers + NGINX Load Balancer)" -ForegroundColor Cyan
Write-Host "=========================================" -ForegroundColor Cyan

# 1. Pastikan Chocolatey terinstall
if (-not (Get-Command choco -ErrorAction SilentlyContinue)) {
    Write-Host "Installing Chocolatey..." -ForegroundColor Yellow
    Set-ExecutionPolicy Bypass -Scope Process -Force
    [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
    iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
}

# 1b. Disable Apache permanently to free up Port 80 and 443
Write-Host "Disabling Apache Service to free up ports..." -ForegroundColor Yellow
Set-Service -Name Apache2.4 -StartupType Disabled -ErrorAction SilentlyContinue
Stop-Process -Name httpd -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 1

# 2. Check native dependencies (PHP, MySQL, Redis)
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

# 3. Setup NGINX (Khusus port 8080 agar tidak bentrok IIS)
$nginxPath = (Get-Command nginx -ErrorAction SilentlyContinue).Source
if (-not $nginxPath) {
    Write-Host "Installing NGINX via Chocolatey..." -ForegroundColor Yellow
    choco install nginx --params '/port:8080' -y
}

# 3b. Setup Win-ACME (Untuk SSL)
$wacsPath = (Get-Command wacs.exe -ErrorAction SilentlyContinue).Source
if (-not $wacsPath) {
    Write-Host "Installing Win-ACME via Chocolatey..." -ForegroundColor Yellow
    choco install win-acme -y
}

# 4. Setup IIS Features
Write-Host "Installing IIS and CGI Features..." -ForegroundColor Yellow
Enable-WindowsOptionalFeature -Online -FeatureName IIS-WebServerRole, IIS-WebServer, IIS-CommonHttpFeatures, IIS-StaticContent, IIS-DefaultDocument, IIS-DirectoryBrowsing, IIS-HttpErrors, IIS-HttpRedirect, IIS-ApplicationDevelopment, IIS-CGI, IIS-HealthAndDiagnostics, IIS-HttpLogging, IIS-RequestMonitor, IIS-Security, IIS-RequestFiltering, IIS-Performance, IIS-HttpCompressionStatic, IIS-WebServerManagementTools, IIS-ManagementConsole -All -NoRestart

# 5. Konfigurasi PHP FastCGI
Write-Host "Configuring PHP FastCGI..." -ForegroundColor Yellow
$phpPath = (Get-Command php.exe -ErrorAction SilentlyContinue).Source
if ($phpPath) {
    $phpDir = Split-Path $phpPath -Parent
    $phpCgiPath = "$phpDir\php-cgi.exe"
    $appcmd = "$env:systemroot\system32\inetsrv\appcmd.exe"

    # Daftarkan FastCGI & Handler
    & $appcmd set config /section:system.webServer/fastCgi "/+`[fullPath='$phpCgiPath'`]" /commit:apphost 2>&1 | Out-Null
    & $appcmd set config /section:system.webServer/handlers "/+`[name='PHP_via_FastCGI',path='*.php',verb='*',modules='FastCgiModule',scriptProcessor='$phpCgiPath',resourceType='Either'`]" /commit:apphost 2>&1 | Out-Null

    # Tambahkan index.php ke default document
    & $appcmd set config /section:defaultDocument "/+files.[value='index.php']" /commit:apphost 2>&1 | Out-Null
}

# 6. Set Permissions untuk Folder Laravel
Write-Host "Setting Folder Permissions..." -ForegroundColor Yellow
$acl = Get-Acl $ProjectPath
$rule = New-Object System.Security.AccessControl.FileSystemAccessRule("IIS_IUSRS", "FullControl", "ContainerInherit,ObjectInherit", "None", "Allow")
$acl.AddAccessRule($rule)
Set-Acl -Path $ProjectPath -AclObject $acl

# 7. Setup IIS Workers (9001, 9002, 9003)
Write-Host "Setting up IIS Workers..." -ForegroundColor Yellow
Import-Module WebAdministration

# Hapus Default Web Site bawaan IIS yang membajak Port 80
if (Get-Website -Name "Default Web Site" -ErrorAction SilentlyContinue) {
    Write-Host "Menghapus 'Default Web Site' bawaan IIS untuk membebaskan Port 80..." -ForegroundColor Yellow
    Remove-Website -Name "Default Web Site"
    Start-Sleep -Seconds 1
}

$appPoolName = "MischoolAppPool"
if (-not (Get-ChildItem IIS:\AppPools | Where-Object { $_.Name -eq $appPoolName })) {
    New-WebAppPool -Name $appPoolName | Out-Null
    Set-ItemProperty "IIS:\AppPools\$appPoolName" -Name "processModel.identityType" -Value "ApplicationPoolIdentity"
}

$workers = @(9001, 9002, 9003)
$workerIndex = 1

foreach ($port in $workers) {
    $siteName = "${Domain}_worker_${workerIndex}"

    # Hapus jika sudah ada
    if (Get-Website -Name $siteName -ErrorAction SilentlyContinue) {
        Remove-Website -Name $siteName
        Start-Sleep -Seconds 1
    }

    # Buat Site Worker
    New-Website -Name $siteName -Port $port -PhysicalPath "$ProjectPath\public" -ApplicationPool $appPoolName -Force | Out-Null
    Write-Host "Created Worker $workerIndex on port $port" -ForegroundColor Green
    $workerIndex++
}

# Buat web.config Laravel standar untuk Worker
$workerWebConfig = @'
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Laravel Force" stopProcessing="true">
                    <match url="^" ignoreCase="false" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
'@
[System.IO.File]::WriteAllText("$ProjectPath\public\web.config", $workerWebConfig)

# 8. Setup NGINX Load Balancer
Write-Host "Configuring NGINX Load Balancer..." -ForegroundColor Yellow

# Matikan Site IIS utama jika masih nyangkut di 8080
if (Get-Website -Name $Domain -ErrorAction SilentlyContinue) {
    Remove-Website -Name $Domain
    Start-Sleep -Seconds 1
}

# Cari folder NGINX dinamis
$nginxDir = Get-ChildItem -Path "C:\tools\nginx*" -Directory -ErrorAction SilentlyContinue | Select-Object -ExpandProperty FullName -First 1
if (-not $nginxDir) {
    $nginxDir = "C:\tools\nginx-1.29.7" # Fallback
}

$nginxConfPath = "$nginxDir\conf\nginx.conf"

$nginxConfig = @"
worker_processes  1;

events {
    worker_connections  1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;
    sendfile        on;
    keepalive_timeout  65;

    # Daftar Worker IIS
    upstream laravel_workers {
        server 127.0.0.1:9001;
        server 127.0.0.1:9002;
        server 127.0.0.1:9003;
    }

    server {
        listen       0.0.0.0:80;
        listen       0.0.0.0:8080;
        server_name  lms.smkmuh04byl.sch.id localhost;

        # Redirect HTTP ke HTTPS secara otomatis
        return 301 https://lms.smkmuh04byl.sch.id`$request_uri;

        location / {
            proxy_pass http://laravel_workers;
            proxy_set_header Host `$http_host;
            proxy_set_header X-Real-IP `$remote_addr;
            proxy_set_header X-Forwarded-For `$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto `$scheme;
            proxy_set_header X-Forwarded-Port `$server_port;

            # Anti lag buffering
            proxy_buffering off;
            proxy_read_timeout 300s;
        }
    }

    # HTTPS Server Block (Active for Win-ACME SSL)
    server {
        listen       0.0.0.0:443 ssl;
        server_name  lms.smkmuh04byl.sch.id;

    ssl_certificate      C:/tools/nginx-1.29.7/conf/ssl/lms.smkmuh04byl.sch.id-chain.pem;
        ssl_certificate_key  C:/tools/nginx-1.29.7/conf/ssl/lms.smkmuh04byl.sch.id-key.pem;

        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_prefer_server_ciphers on;

        location / {
            proxy_pass http://laravel_workers;
            proxy_set_header Host `$http_host;
            proxy_set_header X-Real-IP `$remote_addr;
            proxy_set_header X-Forwarded-For `$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto https;
            proxy_set_header X-Forwarded-Port 443;

            proxy_buffering off;
            proxy_read_timeout 300s;
        }
    }
}
"@

# Tulis config NGINX tanpa BOM
[System.IO.File]::WriteAllText($nginxConfPath, $nginxConfig)

# Restart NGINX
Stop-Process -Name nginx -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 2
Set-Location $nginxDir
Start-Process -FilePath ".\nginx.exe" -WindowStyle Hidden -ErrorAction SilentlyContinue
Write-Host "NGINX Load Balancer running on port 8080 and 443" -ForegroundColor Green

# 9. Konfigurasi Background Jobs Laravel & Optimasi
Write-Host "Configuring Laravel Queue Workers, Scheduler, and Optimization..." -ForegroundColor Yellow

# 9a. Optimasi Laravel (Clear Cache & Config)
Write-Host "Running php artisan optimize..." -ForegroundColor Yellow
& $phpPath "$ProjectPath\artisan" optimize:clear
& $phpPath "$ProjectPath\artisan" optimize
Start-Sleep -Seconds 1

# 9b. Scheduler
$action = New-ScheduledTaskAction -Execute "$phpPath" -Argument "$ProjectPath\artisan schedule:run" -WorkingDirectory $ProjectPath
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1)
$principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -LogonType ServiceAccount -RunLevel Highest
$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable

Unregister-ScheduledTask -TaskName "LaravelScheduler_Mischool" -Confirm:$false -ErrorAction SilentlyContinue
Register-ScheduledTask -TaskName "LaravelScheduler_Mischool" -Action $action -Trigger $trigger -Principal $principal -Settings $settings -Description "Laravel Scheduler" | Out-Null

# 9b. Queue Worker Paralel via NSSM
$nssmPath = (Get-Command nssm -ErrorAction SilentlyContinue).Source
if ($nssmPath) {
    $baseServiceName = "MischoolQueueWorker"
    $numberOfWorkers = 3

    for ($i = 1; $i -le $numberOfWorkers; $i++) {
        $serviceName = "${baseServiceName}_${i}"

        if (Get-Service $serviceName -ErrorAction SilentlyContinue) {
            Stop-Service $serviceName -Force -ErrorAction SilentlyContinue
            & $nssmPath remove $serviceName confirm
            Start-Sleep -Seconds 2
        }

        & $nssmPath install $serviceName "$phpPath" "$ProjectPath\artisan queue:work --tries=3 --max-time=3600"
        & $nssmPath set $serviceName AppDirectory "$ProjectPath"
        & $nssmPath set $serviceName AppStdout "$ProjectPath\storage\logs\queue-worker-${i}.log"
        & $nssmPath set $serviceName AppStderr "$ProjectPath\storage\logs\queue-worker-${i}-error.log"
        & $nssmPath set $serviceName AppEnvironmentExtra "APP_ENV=production"

        Start-Service $serviceName
        Set-Service $serviceName -StartupType Automatic
    }
    Write-Host "Created $numberOfWorkers Queue Workers" -ForegroundColor Green
}

# 10. Firewall & Finalization
Write-Host "Opening Windows Firewall for port 8080..." -ForegroundColor Yellow
New-NetFirewallRule -DisplayName "Mischool Port 8080" -Direction Inbound -LocalPort 8080 -Protocol TCP -Action Allow -Profile Any -ErrorAction SilentlyContinue | Out-Null

Write-Host "=========================================" -ForegroundColor Magenta
Write-Host "DEPLOYMENT SELESAI!" -ForegroundColor Magenta
Write-Host "Aplikasi dapat diakses di: http://[IP_PUBLIC]:8080" -ForegroundColor Magenta
Write-Host "=========================================" -ForegroundColor Magenta
