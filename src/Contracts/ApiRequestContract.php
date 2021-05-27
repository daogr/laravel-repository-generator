<?php

    namespace Otodev\Contracts;

    /**
     * Interface ApiRequestContract
     * @package Otodev\Contracts
     */
    interface ApiRequestContract {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules();
    }
