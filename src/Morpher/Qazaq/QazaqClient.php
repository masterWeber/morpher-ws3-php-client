<?php


namespace Morpher\Qazaq;


use Morpher\Client;
use Morpher\Communicator\ArgumentEmptyException;
use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;

class QazaqClient extends Client
{
    const PREFIX = '/qazaq';

    public function declension(string $phrase, string ...$flags): DeclensionResult
    {
        $params = [
          's' => $phrase,
        ];

        if (count($flags) > 0) {
            $params['flags'] = implode(',', $flags);
        }

        $path = self::PREFIX . '/declension';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
        } catch (InvalidServerResponseException $exception) {

            $msg = $exception->getMessage();
            $code = $exception->getCode();

            switch ($exception->getStatusCode()) {
                case 400:
                    throw new ArgumentEmptyException($msg, $code);
                case 496:
                    throw new ArgumentNotQazaqException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return new DeclensionResult($data);
    }
}