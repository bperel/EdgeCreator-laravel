<?php


namespace App\Exceptions;

use Exception;

class InvalidSessionException extends Exception
{
    public function __construct($message = "You are not authenticated!")
    {
        parent::__construct($message);
    }
}
