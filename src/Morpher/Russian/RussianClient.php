<?php


namespace Morpher\Russian;


use DateTime;
use Morpher\Client;
use Morpher\Communicator\ArgumentEmptyException;
use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;

class RussianClient extends Client
{
    const FLAG_FEMININE = 'feminine';
    const FLAG_MASCULINE = 'masculine';
    const FLAG_ANIMATE = 'animate';
    const FLAG_INANIMATE = 'inanimate';
    const FLAG_COMMON = 'common';
    const FLAG_NAME = 'name';

    const PREFIX = '/russian';

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
                    throw new ArgumentNotRussianException($msg, $code);
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
                    throw new ArgumentNotRussianException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return new NumberSpellingResult($data);
    }

    public function spellOrdinal(int $number, string $unit): NumberSpellingResult
    {
        $params = [
          'n' => $number,
          'unit' => $unit,
        ];

        $path = self::PREFIX . '/spell-ordinal';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
        } catch (InvalidServerResponseException $exception) {

            $msg = $exception->getMessage();
            $code = $exception->getCode();

            switch ($exception->getStatusCode()) {
                case 400:
                    throw new ArgumentEmptyException($msg, $code);
                case 496:
                    throw new ArgumentNotRussianException($msg, $code);
                default:
                    throw $exception;
            }
        }

        return new NumberSpellingResult($data);
    }

    public function spellDate(DateTime $date): DateSpellingResult
    {
        $params = [
          'date' => $date->format('Y-m-d'),
        ];

        $path = self::PREFIX . '/spell-date';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
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

        return new DateSpellingResult($data);
    }

    public function adjectiveGenders(string $adjective): AdjectiveGendersResult
    {
        $params = [
          's' => $adjective,
        ];

        $path = self::PREFIX . '/genders';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
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

        return new AdjectiveGendersResult($data);
    }

    public function adjectivize(string $lemma): array
    {
        $params = [
          's' => $lemma,
        ];

        $path = self::PREFIX . '/adjectivize';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_GET);
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

        return $data;
    }

    public function addStressMarks(string $text): string
    {
        $params = [
          Communicator::CONTENT_BODY_KEY => $text,
        ];

        $path = self::PREFIX . '/addstressmarks';

        try {
            $data = $this->communicator->request($path, $params, Communicator::METHOD_POST);
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

        return (string)$data;
    }
}