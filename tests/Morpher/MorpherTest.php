<?php

namespace Morpher;


use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;
use Morpher\Communicator\MockCommunicator;
use Morpher\Qazaq\QazaqClient;
use Morpher\Russian\RussianClient;
use Morpher\Ukrainian\UkrainianClient;
use PHPUnit\Framework\TestCase;

class MorpherTest extends TestCase
{
    public function testCommunicatorReplacement(): void
    {
        $morpher = new class extends Morpher {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct(null, null, null, $communicator);
            }
        };

        $this->assertInstanceOf(
          MockCommunicator::class,
          $morpher->communicator
        );
    }

    public function testFacade(): void
    {
        $morpher = new Morpher();

        $this->assertInstanceOf(
          RussianClient::class,
          $morpher->russian
        );

        $this->assertInstanceOf(
          UkrainianClient::class,
          $morpher->ukrainian
        );

        $this->assertInstanceOf(
          QazaqClient::class,
          $morpher->qazaq
        );
    }

    public function testGetQueriesLeft(): void
    {
        $communicatorMock = new MockCommunicator();
        $morpher = new Morpher(null, null, null, $communicatorMock);

        $response = 99;
        $communicatorMock->response = $response;

        $this->assertEquals(
          $response,
          $morpher->getQueriesLeft()
        );

        $response = 0;
        $communicatorMock->response = $response;

        $this->assertEquals(
          $response,
          $morpher->getQueriesLeft()
        );
    }

    public function testThrowException(): void
    {
        $this->expectException(InvalidServerResponseException::class);

        $communicatorMock = new MockCommunicator();
        $morpher = new Morpher(null, null, null, $communicatorMock);

        $communicatorMock->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $morpher->getQueriesLeft();
    }
}
