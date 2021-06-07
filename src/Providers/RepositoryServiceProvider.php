<?php

	namespace Otodev\Providers;

	use Illuminate\Support\ServiceProvider;
	use Otodev\Contracts\ValidatorContract;
	use Otodev\Validator\RepositoryValidator;

	/**
	 * Class RepositoryServiceProvider
	 * @package Otodev\Providers
	 */
	class RepositoryServiceProvider extends ServiceProvider {

		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public function boot() {
			$this->commands([
				'Otodev\Console\Commands\ApiModelMakeCommand',
				'Otodev\Console\Commands\ApiControllerMakeCommand',
				'Otodev\Console\Commands\ApiLanguageFileMakeCommand',
				'Otodev\Console\Commands\ApiPolicyMakeCommand',
				'Otodev\Console\Commands\ApiRepositoryContractMakeCommand',
				'Otodev\Console\Commands\ApiRepositoryMakeCommand',
				'Otodev\Console\Commands\ApiRequestMakeCommand',
				'Otodev\Console\Commands\ApiResourceMakeCommand',
				'Otodev\Console\Commands\CreateApi',
				'Otodev\Console\Commands\TraitMakeCommand',
			]);

			$this->publishes([
				__DIR__ . '/../../config/repository.php' => config_path('repository.php')
			], 'repo-generator-config');

			// Validator
			$this->app->bind(ValidatorContract::class, RepositoryValidator::class);
		}

		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register() {
			$this->mergeConfigFrom(__DIR__ . '/../../config/repository.php', 'repository');
		}
	}
