<?php

    namespace Otodev\Providers;

    use Illuminate\Support\Facades\Validator;
    use Illuminate\Support\ServiceProvider;

	/**
	 * Class ValidationServiceProvider
	 * @package Otodev\Providers
	 */
    class ValidationServiceProvider extends ServiceProvider {

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot() {
            Validator::extend('slug', 'Otodev\Rules\SlugRule@passes');
            Validator::extend('code', 'Otodev\Rules\CodeRule@passes');
            Validator::extend('decimal', 'Otodev\Rules\DecimalRule@passes');
        }

        /**
         * Register services.
         *
         * @return void
         */
        public function register() {
            //
        }
    }
