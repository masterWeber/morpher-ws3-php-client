<?php


namespace Morpher;


use Morpher\Communicator\Communicator;

abstract class Client
{
    protected Communicator $communicator;

    public function __construct(Communicator $communicator)
    {
        $this->communicator = $communicator;
    }
}