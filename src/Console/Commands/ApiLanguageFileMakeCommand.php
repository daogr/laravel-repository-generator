<?php

	namespace Otodev\Console\Commands;

    use Illuminate\Console\GeneratorCommand;
    use Illuminate\Contracts\Filesystem\FileNotFoundException;
    use Illuminate\Support\Str;
    use Symfony\Component\Console\Input\InputArgument;

    class ApiLanguageFileMakeCommand extends GeneratorCommand {

        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $name = 'make:lang:api';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Create a new api language file';

        /**
         * Get the stub file for the generator.
         *
         * @return string
         */
        protected function getStub() {
            $lang = $this->getLangInput();

            return __DIR__ . "/stubs/api_language_file_$lang.stub";
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

            return $this->replaceNamespace($stub, $name)->replaceClassUpper($stub, $name)->replaceClass($stub, $name);
        }

        /**
         * Replace the class name upper for the given stub.
         *
         * @param string $stub
         * @param string $name
         *
         * @return $this
         */
        protected function replaceClassUpper(&$stub, $name) {
            $class = str_replace($this->getNamespace($name) . '\\', '', $name);
            $stub  = str_replace('DummyClassUpper', Str::ucfirst($class), $stub);

            return $this;
        }

        /**
         * Get the destination class path.
         *
         * @param string $name
         *
         * @return string
         */
        protected function getPath($name) {
            $lang = $this->getLangInput();

            $first = Str::before($this->getNameInput(), '\\');
            $last  = Str::afterLast($this->getNameInput(), '\\');
            $name  = Str::lower($first) . '/' . Str::snake($last);

            return resource_path('lang') . '/' . $lang . '/' . $name . '.php';
        }

        /**
         * Get the console command arguments.
         *
         * @return array
         */
        protected function getArguments() {
            return [
                ['name', InputArgument::REQUIRED, 'The name of the class'],
                ['lang', InputArgument::REQUIRED, 'The lang of the file'],
            ];
        }

        /**
         * Get the desired class name from the input.
         *
         * @return string
         */
        protected function getNameInput() {
            return trim($this->argument('name'));
        }

        /**
         * Get the desired lang name from the input.
         *
         * @return string
         */
        protected function getLangInput() {
            return Str::lower(trim($this->argument('lang')));
        }
    }
