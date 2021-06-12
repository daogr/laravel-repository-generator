<?php

    namespace Otodev\Events;

    use Otodev\Contracts\RepositoryContract;
    use Illuminate\Database\Eloquent\Model;

    /**
     * Class RepositoryEventBase
     * @package Otodev\Events
     */
    abstract class RepositoryEventBase {
        /**
         * @var Model
         */
        protected $model;

        /**
         * @var RepositoryContract
         */
        protected $repository;

        /**
         * @var string
         */
        protected $action;

        /**
         * @param RepositoryContract $repository
         * @param Model              $model
         */
        public function __construct(RepositoryContract $repository, Model $model) {
            $this->repository = $repository;
            $this->model      = $model;
        }

        /**
         * @return Model
         */
        public function getModel() {
            return $this->model;
        }

        /**
         * @return RepositoryContract
         */
        public function getRepository() {
            return $this->repository;
        }

        /**
         * @return string
         */
        public function getAction() {
            return $this->action;
        }
    }
