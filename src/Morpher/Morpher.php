<?php


namespace Morpher;


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
      ?string $token,
      ?string $url,
      ?int $timeout,
      ?Communicator $communicator
    ) {
        if ($communicator) {
            $this->communicator = $communicator;
        } else {
            $this->communicator = new Communicator($token, $url, $timeout);
        }

        $this->russian = new RussianClient($this->communicator);
        $this->ukrainian = new UkrainianClient($this->communicator);
        $this->qazaq = new QazaqClient($this->communicator);
    }


}