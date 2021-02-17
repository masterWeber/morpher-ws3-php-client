<?php


namespace Morpher;


abstract class Client
{
    protected Communicator $communicator;

    public function __construct(Communicator $communicator)
    {
        $this->communicator = $communicator;
    }
}