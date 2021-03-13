<?php


namespace Morpher\Communicator;


use Exception;
use stdClass;

class MockCommunicator implements Communicator
{
    /**
     * @var stdClass|int|array|string|bool
     */
    public $response;
    public ?Exception $exception = null;

    /**
     * @param string $path
     * @param array $params
     * @param string $method
     * @return stdClass|int|array|string|bool
     * @throws Exception
     */
    public function request(string $path, array $params, string $method)
    {
        if ($this->exception) {
            throw $this->exception;
        }

        return $this->response;
    }
}