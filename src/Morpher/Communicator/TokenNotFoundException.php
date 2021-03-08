<?php


namespace Morpher\Communicator;


use Exception;
use Throwable;

class TokenNotFoundException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}