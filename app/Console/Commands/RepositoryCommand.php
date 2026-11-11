<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RepositoryCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make an repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $directory = app_path('Contracts/Repositories');
        $filePath = $directory . '/' . $name . 'Repository.php';

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error('Repository already exists!');
            return;
        }

        [$namespace, $classname] = $this->separateNamespaceAndClass($name);
        if($namespace) {
            $stub = <<<PHP
            <?php

            namespace App\Contracts\Repositories\\{$namespace}\\{$classname};

            use App\Contracts\Interfaces\\{$namespace}\\{$classname}Interface;
            use App\Contracts\Repositories\BaseRepository;
            use App\Models\\{$classname};

            class {$classname}Repository extends BaseRepository implements {$classname}Interface
            {
                public function __construct({$classname} \${$classname})
                {
                    \$this->model = \${$classname};
                }

                public function get(): mixed
                {
                    return \$this->model->query()->get();
                }

                public function store(array \$data): mixed
                {
                    return \$this->model->query()->create(\$data);
                }

                public function show(mixed \$id): mixed
                {
                    return \$this->model->query()->findOrFail(\$id);
                }

                public function update(mixed \$id, array \$data): mixed
                {
                    return \$this->model->query()->findOrFail(\$id)->update(\$data);
                }

                public function delete(mixed \$id): mixed
                {
                    return \$this->model->query()->findOrFail(\$id)->delete();
                }
            }

            PHP;
        } else {
            $stub = <<<EOT
                <?php

                namespace App\Contracts\Repositories;

                use App\Contracts\Interfaces\\{$name}Interface;
                use App\Models\\{$name};

                class {$name}Repository extends BaseRepository implements {$name}Interface
                {
                    public function __construct({$name} \${$name})
                    {
                        \$this->model = \${$name};
                    }

                    public function get(): mixed
                    {
                        return \$this->model->query()->get();
                    }

                    public function store(array \$data): mixed
                    {
                        return \$this->model->query()->create(\$data);
                    }

                    public function show(mixed \$id): mixed
                    {
                        return \$this->model->query()->findOrFail(\$id);
                    }

                    public function update(mixed \$id, array \$data): mixed
                    {
                        return \$this->model->query()->findOrFail(\$id)->update(\$data);
                    }

                    public function delete(mixed \$id): mixed
                    {
                        return \$this->model->query()->findOrFail(\$id)->delete();
                    }
                }
                EOT;
        }

        $dirname = dirname($filePath);
        if(!File::isDirectory($dirname)){
            File::makeDirectory($dirname, 0755, true, true);
        }

        File::put($filePath, $stub);

        $this->info("Repository {$name} created successfully. at {$filePath}");
    }

    /**
     * Separate classname namespace if exists
     *
     * @param string $classname Fully Qualified Class Name of a Class
     * @return array{0: string, 1: string} first is namespace and second is classname
     */
    private function separateNamespaceAndClass(string $classname): array
    {
        $normalized = str_replace('/\\', '/', $classname);
        $parts = explode('/', $normalized);
        if(($count = count($parts)) < 2){
            return [null, $parts[0]];
        }

        return [implode('\\',array_slice($parts, 1)), $parts[$count-1]];
    }
}
