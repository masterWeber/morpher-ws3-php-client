<?php


namespace Morpher\Communicator;


use Exception;
use Throwable;

class InvalidServerResponseException extends Exception
{
    protected int $statusCode;

    public function __construct(int $status, string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $status;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}