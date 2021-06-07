<?php

	namespace Otodev\Console\Commands;

	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Artisan;

	class CreateApi extends Command {
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'make:api:all {name} {import}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Create all api resources';

		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct() {
			parent::__construct();
		}

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle() {
			Artisan::call('make:model:api', ['name' => $this->argument('name')]);
			$this->info('- Api Model created.');

			Artisan::call('make:request:api', ['name' => $this->argument('name'), 'import' => $this->argument('import'), 'type' => 'create']);
			$this->info('- Api Create Request created.');

			Artisan::call('make:request:api', ['name' => $this->argument('name'), 'import' => $this->argument('import'), 'type' => 'update']);
			$this->info('- Api Update Request created.');

			Artisan::call('make:repository_contract:api', ['name' => $this->argument('name')]);
			$this->info('- Api Repository Contract created.');

			Artisan::call('make:repository:api', ['name' => $this->argument('name')]);
			$this->info('- Api Repository created.');

			Artisan::call('make:lang:api', ['name' => $this->argument('name'), 'lang' => 'el']);
			$this->info('- Api Language el file created.');

			Artisan::call('make:lang:api', ['name' => $this->argument('name'), 'lang' => 'en']);
			$this->info('- Api Language en file created.');

			Artisan::call('make:policy:api', ['name' => $this->argument('name')]);
			$this->info('- Api Policy created.');

			Artisan::call('make:resource:api', ['name' => $this->argument('name'), 'type' => 'create']);
			$this->info('- Api Create Resource created.');

			Artisan::call('make:resource:api', ['name' => $this->argument('name'), 'type' => 'update']);
			$this->info('- Api Update Resource created.');
			
			Artisan::call('make:resource:api', ['name' => $this->argument('name'), 'type' => 'createOrUpdate']);
			$this->info('- Api CreateOrUpdate Resource created.');
			
			Artisan::call('make:resource:api', ['name' => $this->argument('name'), 'type' => 'list']);
			$this->info('- Api List Resource created.');

			Artisan::call('make:resource:api', ['name' => $this->argument('name'), 'type' => 'show']);
			$this->info('- Api Show Resource created.');

			Artisan::call('make:controller:api', ['name' => $this->argument('name')]);
			$this->info('- Api Controller created.');

			$this->info('All api resources created successfully.');
		}
	}
