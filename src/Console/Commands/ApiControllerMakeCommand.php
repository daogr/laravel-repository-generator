<?php

namespace Otodev\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ApiControllerMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:controller:api';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api controller class';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath("{$name}Controller");

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') ||
                !$this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type . ' created successfully.');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *D
     *
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceImportClass($stub)->replaceTransClass($stub)->replaceClass($stub, $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/api_controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers\API';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    /**
     * Replace the use import class name for the given stub.
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function replaceImportClass(&$stub)
    {
        $stub = str_replace('UseDummyClass', $this->getNameInput(), $stub);

        return $this;
    }

    /**
     * Replace the use translation file path for the given stub.
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function replaceTransClass(&$stub)
    {
        $first = Str::before($this->getNameInput(), '\\');
        $last = Str::afterLast($this->getNameInput(), '\\');
        $name = Str::lower($first) . '/' . Str::snake($last);

        $stub = str_replace('DummyTrans', $name, $stub);

        return $this;
    }

}
