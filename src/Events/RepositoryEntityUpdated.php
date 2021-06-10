<?php

    namespace Otodev\Events;

    class RepositoryEntityUpdated extends RepositoryEventBase {
        /**
         * @var string
         */
        protected $action = "updated";
    }
