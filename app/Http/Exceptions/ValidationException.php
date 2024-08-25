<?php

namespace App\Http\Exceptions;

use Illuminate\Validation\ValidationException as Exception;

class ValidationException extends Exception
{

    public function __construct($validator, $response = null, $errorBag = 'default')
    {

        parent::__construct($validator, $response, $errorBag);

    }

    public function setMessage($message) {

        $this->message = $message;

    }

}
