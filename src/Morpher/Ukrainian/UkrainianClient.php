<?php


namespace Morpher\Ukrainian;


use Morpher\Client;
use Morpher\Communicator\ArgumentEmptyException;
use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;
use Morpher\Russian\InvalidFlagsException;
use Morpher\Russian\NumeralsDeclensionNotSupportedException;

class UkrainianClient extends Client
{
    const FLAG_FEMININE = 'feminine';
    const FLAG_MASCULINE = 'masculine';
    const FLAG_NEUTER = 'neuter';
    const FLAG_PLURAL = 'plural';

    const PREFIX = '/ukrainian';

    public UserDict $userDict;

    public function __construct(Communicator $communicator)
    {
        parent::__construct($communicator);
        $this->userDict = new UserDict($communicator);
    }

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
                case 494:
                    throw new InvalidFlagsException($msg, $code);
                case 495:
                    throw new NumeralsDeclensionNotSupportedException($msg, $code);
                case 496:
                    throw new ArgumentNotUkrainianException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return new DeclensionResult($data);
    }

    public function spell(int $number, string $unit): NumberSpellingResult
    {
        $params = [
          'n' => $number,
          'unit' => $unit,
        ];

        $path = self::PREFIX . '/spell';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
        } catch (InvalidServerResponseException $exception) {

            $msg = $exception->getMessage();
            $code = $exception->getCode();

            switch ($exception->getStatusCode()) {
                case 400:
                    throw new ArgumentEmptyException($msg, $code);
                case 496:
                    throw new ArgumentNotUkrainianException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return new NumberSpellingResult($data);
    }
}