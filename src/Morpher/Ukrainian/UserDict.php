<?php


namespace Morpher\Ukrainian;


use Morpher\Communicator\ArgumentEmptyException;
use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;

class UserDict
{
    const PREFIX = '/ukrainian/userdict';

    protected Communicator $communicator;

    public function __construct($communicator)
    {
        $this->communicator = $communicator;
    }

    public function addOrUpdate(CorrectionEntry $entry): void
    {
        $params = $entry->toNameValueArray();

        try {
            $this->communicator->request(self::PREFIX, $params, Communicator::METHOD_POST);
        } catch (InvalidServerResponseException $exception) {

            $msg = $exception->getMessage();
            $code = $exception->getCode();

            switch ($exception->getStatusCode()) {
                case 400:
                    throw new ArgumentEmptyException($msg, $code);
                default:
                    throw $exception;
            }
        }
    }

    public function remove(string $nominativeForm): bool
    {
        $params = [
          's' => $nominativeForm,
        ];

        try {
            $data = $this->communicator->request(self::PREFIX, $params, Communicator::METHOD_DELETE);
        } catch (InvalidServerResponseException $exception) {

            $msg = $exception->getMessage();
            $code = $exception->getCode();

            switch ($exception->getStatusCode()) {
                case 400:
                    throw new ArgumentEmptyException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return (bool)$data;
    }

    /**
     * @return CorrectionEntry[]
     * @throws InvalidServerResponseException
     */
    public function getAll(): array
    {
        $data = $this->communicator->request(self::PREFIX, [], Communicator::METHOD_GET);

        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = new CorrectionEntry($data[$i]);
        }

        return $data;
    }

}