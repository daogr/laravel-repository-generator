<?php

	namespace Otodev\Console\Commands;

	use Illuminate\Console\GeneratorCommand;
	use Illuminate\Contracts\Filesystem\FileNotFoundException;
	use Symfony\Component\Console\Input\InputArgument;

	class ApiPolicyMakeCommand extends GeneratorCommand {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $name = 'make:policy:api';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Create a new api policy class';

		/**
		 * Execute the console command.
		 *
		 * @return bool|null
		 *
		 * @throws FileNotFoundException
		 */
		public function handle() {
			$name = $this->qualifyClass($this->getNameInput());

			$path = $this->getPath("{$name}Policy");

			// First we will check to see if the class already exists. If it does, we don't want
			// to create the class and overwrite the user's code. So, we will bail out so the
			// code is untouched. Otherwise, we will continue generating this class' files.
			if((!$this->hasOption('force') ||
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
		 * Get the stub file for the generator.
		 *
		 * @return string
		 */
		protected function getStub() {
			return __DIR__ . '/stubs/api_policy.stub';
		}

		/**
		 * Get the default namespace for the class.
		 *
		 * @param string $rootNamespace
		 *
		 * @return string
		 */
		protected function getDefaultNamespace($rootNamespace) {
			return $rootNamespace . '\Policies';
		}

		/**
		 * Get the console command arguments.
		 *
		 * @return array
		 */
		protected function getArguments() {
			return [
				['name', InputArgument::REQUIRED, 'The name of the class'],
			];
		}
	}
