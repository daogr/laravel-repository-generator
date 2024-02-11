<?php

namespace Otodev\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ApiResourceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:resource:api';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new api resource class';

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

        $path = $this->getPath("{$name}Resource");

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
        $name = trim($this->argument('name'));
        $type = $this->getTypeInput();

        $this->type = "Api request {$type} class";

        if (in_array($type, ['create', 'update', 'list', 'show'])) {

            $typeName = Str::studly($type);

            if ($type == 'create') {
                $typeName = 'Store';
            }

            return "{$name}{$typeName}";
        }

        return $name;
    }

    /**
     * Get the desired class type from the input.
     *
     * @return string
     */
    protected function getTypeInput()
    {
        return trim($this->argument('type'));
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceTraitClass($stub)->replaceClass($stub, $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/api_resource.stub';
    }

    /**
     * Replace the trait class name for the given stub.
     *
     * @param string $stub
     *
     * @return $this
     */
    protected function replaceTraitClass(&$stub)
    {
        $class = $this->getTraitClass();

        $stub = str_replace('DummyTraitClass', $class, $stub);

        return $this;
    }

    /**
     * Get the desired trait class name based on type.
     *
     * @return string
     */
    protected function getTraitClass()
    {
        $type = $this->getTypeInput();

        if (in_array($type, ['create', 'update'])) {
            return 'UseResourceTransform';
        }

        if (in_array($type, ['list', 'show'])) {
            return 'UseResourceFillable';
        }

        return '';
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
        return $rootNamespace . '\Http\Resources';
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
            ['type', InputArgument::REQUIRED, 'The type of the class'],
        ];
    }
}
