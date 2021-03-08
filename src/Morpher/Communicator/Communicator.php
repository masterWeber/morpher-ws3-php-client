<?php


namespace Morpher\Communicator;


use stdClass;

interface Communicator
{
    const METHOD_GET = 'GET';
    const METHOD_DELETE = 'DELETE';
    const METHOD_POST = 'POST';
    const CONTENT_BODY_KEY = 'Content-Body';

    /**
     * @param string $path
     * @param array $params
     * @param string $method
     * @return stdClass|int|array|string|bool
     * @throws InvalidServerResponseException
     */
    public function request(string $path, array $params, string $method);
}