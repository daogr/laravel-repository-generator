<?php

    namespace Otodev\Events;
    
    class ImageUploadCreated extends RepositoryEventBase {
        /**
         * @var string
         */
        protected $action = "created";
    }
