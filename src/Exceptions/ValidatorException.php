<?php

namespace Otodev\Exceptions;

use Illuminate\Validation\ValidationException;

/**
 * Class ValidatorException
 * @package Otodev\Exceptions
 */
class ValidatorException extends ValidationException
{
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);
        /**
         * Set the message
         */
        $this->message = __('validation.exception_message');
    }
}
