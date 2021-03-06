<?php


namespace Morpher\Russian;


use Exception;
use Throwable;

class NumeralsDeclensionNotSupportedException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}