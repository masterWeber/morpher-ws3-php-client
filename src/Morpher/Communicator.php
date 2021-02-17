<?php


namespace Morpher;


class Communicator
{
    const METHOD_GET = 'GET';
    const METHOD_DELETE = 'DELETE';
    const METHOD_POST = 'POST';
    const CONTENT_BODY_KEY = 'Content-Body';

    protected ?string $token = null;
    protected string $url = 'https://ws3.morpher.ru';
    protected int $timeout = 3000;

    public function __construct(?string $token, ?string $url, ?int $timeout)
    {
        if ($token) {
            $this->token = $token;
        }

        if ($url) {
            $this->url = $url;
        }

        if ($timeout) {
            $this->timeout = $timeout;
        }

    }

    public function request(string $path, array $params, string $method)
    {}

}