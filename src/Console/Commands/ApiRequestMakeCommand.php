<?php

	namespace Otodev\Console\Commands;

    use Illuminate\Console\GeneratorCommand;
    use Illuminate\Contracts\Filesystem\FileNotFoundException;
    use Illuminate\Support\Str;
    use Symfony\Component\Console\Input\InputArgument;

    /**
     * Class ApiRequestMakeCommand
     * @package Otodev\Commands
     */
    class ApiRequestMakeCommand extends GeneratorCommand {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $name = 'make:request:api';
        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Create a new api request class';

        /**
         * Get the stub file for the generator.
         *
         * @return string
         */
        protected function getStub() {
            return __DIR__ . '/stubs/api_request.stub';
        }

        /**
         * Get the default namespace for the class.
         *
         * @param string $rootNamespace
         *
         * @return string
         */
        protected function getDefaultNamespace($rootNamespace) {
            return $rootNamespace . '\Http\Requests';
        }

        /**
         * Get the console command arguments.
         *
         * @return array
         */
        protected function getArguments() {
            return [
                ['name', InputArgument::REQUIRED, 'The name of the class'],
                ['import', InputArgument::REQUIRED, 'The name of the imported class'],
                ['type', InputArgument::REQUIRED, 'The type of the class'],
            ];
        }

        /**
         * Get the desired class name from the input.
         *
         * @return string
         */
        protected function getNameInput() {
            $name = trim($this->argument('name'));
            $type = $this->getTypeInput();

            $this->type = "Api request {$type} class";

            if($type == 'create') return "{$name}CreateRequest";
            if($type == 'update') return "{$name}UpdateRequest";

            return $name;
        }

        /**
         * Get the desired class import from the input.
         *
         * @return string
         */
        protected function getImportInput() {
            return trim($this->argument('import'));
        }

        /**
         * Get the desired class type from the input.
         *
         * @return string
         */
        protected function getTypeInput() {
            return trim($this->argument('type'));
        }

        /**
         * Build the class with the given name.
         *
         * @param string $name
         *
         * @return string
         *
         * @throws FileNotFoundException
         */
        protected function buildClass($name) {
            $stub = $this->files->get($this->getStub());
            $type = $this->getTypeInput();

            return $this->replaceNamespace($stub, $name)->replaceImportClass($stub)->replaceClassType($stub, $type)->replaceClass($stub, $name);
        }

        /**
         * Replace the class name for the given stub.
         *
         * @param string $stub
         * @param string $name
         *
         * @return string
         */
        protected function replaceClass($stub, $name) {
            $class = str_replace($this->getNamespace($name) . '\\', '', $name);

            return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
        }

        /**
         * Replace the use import class name for the given stub.
         *
         * @param string $stub
         *
         * @return $this
         */
        protected function replaceImportClass(&$stub) {
            $name = $this->getImportInput();
            $stub = str_replace(['UseDummyImportClass', 'DummyImportClass'], [$name, Str::afterLast($name, '\\')], $stub);

            return $this;
        }

        /**
         * Replace the class type for the given stub.
         *
         * @param string $stub
         * @param string $type
         *
         * @return $this
         */
        protected function replaceClassType(&$stub, $type) {
            $stub = str_replace('DummyClassType', (($type == 'create') ? 'ValidatorContract::RULE_CREATE' : 'ValidatorContract::RULE_UPDATE'), $stub);

            return $this;
        }
    }
