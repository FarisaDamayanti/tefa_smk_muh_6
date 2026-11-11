<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use SebastianBergmann\CodeCoverage\Report\PHP;

class InterfaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name : The name of the interface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make an interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $directory = app_path('Contracts/Interfaces');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->info('Directory created successfully.');
        }

        $filePath = $directory . '/' . $name . 'Interface.php';

        if (File::exists($filePath)) {
            $this->error('Interface already exists!');
            return;
        }
        $addition = '';

        $name = str_replace('/', '\\', $name);
        $name = trim($name, '\\');

        $segments = explode('\\', $name);

        if (count($segments) > 1) {
            $class = array_pop($segments);
            $addition = '\\' . implode('\\', $segments);
        } else {
            $class = $name;
        }

        $interfaceContent = <<<PHP
        <?php

        namespace App\Contracts\Interfaces{$addition};

        use App\Contracts\Interfaces\Eloquent\DeleteInterface;
        use App\Contracts\Interfaces\Eloquent\GetInterface;
        use App\Contracts\Interfaces\Eloquent\ShowInterface;
        use App\Contracts\Interfaces\Eloquent\StoreInterface;
        use App\Contracts\Interfaces\Eloquent\UpdateInterface;

        interface {$class}Interface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
        {
            // Define your methods here
        }
            
        PHP;

        $dirname = dirname($filePath);
        if(!File::isDirectory($dirname)){
            File::makeDirectory($dirname, 0755, true, true);
        }

        File::put($filePath, $interfaceContent);
        $absolutePath = realpath($filePath);
        $this->info("Interface [{$absolutePath}] created successfully.");
    }
}
