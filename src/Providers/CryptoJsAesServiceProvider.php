<?php

    namespace Otodev\Providers;

    use Otodev\Crypto\CryptoJsAes;
    use Illuminate\Support\ServiceProvider;

    class CryptoJsAesServiceProvider extends ServiceProvider {
        /**
         * Register services.
         *
         * @return void
         */
        public function register() {
            $this->app->singleton(CryptoJsAes::class, function($app) {
                return new CryptoJsAes();
            });
        }

        /**
         * Bootstrap services.
         *
         * @return void
         */
        public function boot() {
            //
        }
    }
