<?php

    namespace Otodev\Events;

    class RepositoryEntityDeleted extends RepositoryEventBase {
        /**
         * @var string
         */
        protected $action = "deleted";
    }
