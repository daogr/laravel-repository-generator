<?php

    namespace Otodev\Events;

    class RepositoryEntityCreated extends RepositoryEventBase {
        /**
         * @var string
         */
        protected $action = "created";
    }
