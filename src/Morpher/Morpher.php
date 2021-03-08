<?php


namespace Morpher;


use Morpher\Communicator\Communicator;
use Morpher\Communicator\HttpCommunicator;
use Morpher\Communicator\InvalidServerResponseException;
use Morpher\Qazaq\QazaqClient;
use Morpher\Russian\RussianClient;
use Morpher\Ukrainian\UkrainianClient;

class Morpher
{
    protected Communicator $communicator;

    public RussianClient $russian;
    public UkrainianClient $ukrainian;
    public QazaqClient $qazaq;

    public function __construct(
      ?string $token = null,
      ?string $baseUrl = null,
      ?int $timeoutMs = null,
      ?Communicator $communicator = null
    ) {
        if ($communicator) {
            $this->communicator = $communicator;
        } else {
            $this->communicator = new HttpCommunicator($token, $baseUrl, $timeoutMs);
        }

        $this->russian = new RussianClient($this->communicator);
        $this->ukrainian = new UkrainianClient($this->communicator);
        $this->qazaq = new QazaqClient($this->communicator);
    }

    public function getQueriesLeft(): int
    {
        $path = '/get-queries-left';
        $params = [];
        $method = HttpCommunicator::METHOD_GET;

        return $this->communicator->request($path, $params, $method);
    }
}