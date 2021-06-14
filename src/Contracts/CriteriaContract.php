<?php

    namespace Otodev\Contracts;

    /**
     * Interface CriteriaContract
     * @package Otodev\Contracts
     */
    interface CriteriaContract {
        /**
         * Apply criteria in query repository
         *
         * @param                     $model
         * @param RepositoryContract  $repository
         *
         * @return mixed
         */
        public function apply($model, RepositoryContract $repository);
    }
