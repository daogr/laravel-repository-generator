<?php

	namespace Otodev\Providers;

	use Illuminate\Support\ServiceProvider;

	/**
	 * Class CoreServiceProvider
	 * @package Otodev\Providers
	 */
	class CoreServiceProvider extends ServiceProvider {

		/**
		 * Bootstrap services.
		 *
		 * @return void
		 */
		public function boot() {
			include __DIR__ . './../Core/Helpers/helpers.php';
		}

		/**
		 * Register services.
		 *
		 * @return void
		 */
		public function register() {
			// Core
			$this->app->singleton('core', function($app) {
				return $app->make(config('repository.core', \Otodev\Core\Core::class));
			});
		}
	}
