<?php


namespace Morpher\Qazaq;


use Exception;
use Throwable;

class ArgumentNotQazaqException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}