<?php

namespace Morpher\Russian;

use Codeception\AssertThrows;
use DateTime;
use Morpher\Communicator\ArgumentEmptyException;
use Morpher\Communicator\Communicator;
use Morpher\Communicator\InvalidServerResponseException;
use Morpher\Communicator\MockCommunicator;
use PHPUnit\Framework\TestCase;

class RussianClientTest extends TestCase
{
    use AssertThrows;

    public function testDeclension(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $fullName = (object)[
          'Ф' => 'Соколова',
          'И' => 'Любовь',
          'О' => '',
        ];

        $response = (object)[
          'Р' => 'Любови Соколовой',
          'Д' => 'Любови Соколовой',
          'В' => 'Любовь Соколову',
          'Т' => 'Любовью Соколовой',
          'П' => 'Любови Соколовой',
          'ФИО' => $fullName,
        ];

        $client->communicator->response = $response;

        $client->declension('Любовь Соколова', RussianClient::FLAG_NAME);

        $this->assertEquals(
          '/russian/declension',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          'Любовь Соколова',
          $client->communicator->lastParams['s']
        );

        $this->assertEquals(
          RussianClient::FLAG_NAME,
          $client->communicator->lastParams['flags']
        );

        // should return declensions
        $plural = (object)[
          'И' => 'тесты',
          'Р' => 'тестов',
          'Д' => 'тестам',
          'В' => 'тесты',
          'Т' => 'тестами',
          'П' => 'тестах',
        ];

        $response = (object)[
          'Р' => 'теста',
          'Д' => 'тесту',
          'В' => 'тест',
          'Т' => 'тестом',
          'П' => 'тесте',
          'множественное' => $plural,
        ];

        $client->communicator->response = $response;

        $declensionResult = $client->declension('тест');

        $this->assertEquals(
          $response->Р,
          $declensionResult->genitive
        );

        $this->assertEquals(
          $response->Д,
          $declensionResult->dative
        );

        $this->assertEquals(
          $response->В,
          $declensionResult->accusative
        );

        $this->assertEquals(
          $response->Т,
          $declensionResult->instrumental
        );

        $this->assertEquals(
          $response->П,
          $declensionResult->prepositional
        );

        $this->assertNull(
          $declensionResult->prepositional_O
        );

        $this->assertEquals(
          $response->множественное->Р,
          $declensionResult->plural->genitive
        );

        $this->assertEquals(
          $response->множественное->Д,
          $declensionResult->plural->dative
        );

        $this->assertEquals(
          $response->множественное->В,
          $declensionResult->plural->accusative
        );

        $this->assertEquals(
          $response->множественное->Т,
          $declensionResult->plural->instrumental
        );

        $this->assertEquals(
          $response->множественное->П,
          $declensionResult->plural->prepositional
        );

        $this->assertNull(
          $declensionResult->plural->prepositional_O
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          496,
          'Не найдено русских слов.',
          5
        );

        $this->assertThrows(
          ArgumentNotRussianException::class,
          function () use ($client) {
              $client->declension('test');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: s.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client) {
              $client->declension('');
          }
        );

        $client->communicator->exception = new InvalidServerResponseException(
          494,
          'Указаны неправильные флаги.',
          12
        );

        $this->assertThrows(
          InvalidFlagsException::class,
          function () use ($client) {
              $client->declension('test');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          495,
          'Склонение числительных в declension не поддерживается. Используйте метод spell.',
          4
        );

        $this->assertThrows(
          NumeralsDeclensionNotSupportedException::class,
          function () use ($client) {
              $client->declension('45');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client) {
              $client->declension('Тест');
          });
    }

    public function testSpell(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = (object)[
          'n' => (object)[
            'И' => 'двести тридцать пять',
            'Р' => 'двухсот тридцати пяти',
            'Д' => 'двумстам тридцати пяти',
            'В' => 'двести тридцать пять',
            'Т' => 'двумястами тридцатью пятью',
            'П' => 'двухстах тридцати пяти',
          ],
          'unit' => (object)[
            'И' => 'рублей',
            'Р' => 'рублей',
            'Д' => 'рублям',
            'В' => 'рублей',
            'Т' => 'рублями',
            'П' => 'рублях',
          ],
        ];

        $client->communicator->response = $response;

        $client->spell(235, 'рубль');

        $this->assertEquals(
          '/russian/spell',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          235,
          $client->communicator->lastParams['n']
        );

        $this->assertEquals(
          'рубль',
          $client->communicator->lastParams['unit']
        );

        $this->assertEquals(
          Communicator::METHOD_GET,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $numberSpellingResult = $client->spell(235, 'рубль');

        $this->assertEquals(
          $response->n->Р,
          $numberSpellingResult->n->genitive
        );

        $this->assertEquals(
          $response->n->Д,
          $numberSpellingResult->n->dative
        );

        $this->assertEquals(
          $response->n->В,
          $numberSpellingResult->n->accusative
        );

        $this->assertEquals(
          $response->n->Т,
          $numberSpellingResult->n->instrumental
        );

        $this->assertEquals(
          $response->n->П,
          $numberSpellingResult->n->prepositional
        );

        $this->assertNull(
          $numberSpellingResult->n->prepositional_O
        );

        $this->assertEquals(
          $response->unit->Р,
          $numberSpellingResult->unit->genitive
        );

        $this->assertEquals(
          $response->unit->Д,
          $numberSpellingResult->unit->dative
        );

        $this->assertEquals(
          $response->unit->В,
          $numberSpellingResult->unit->accusative
        );

        $this->assertEquals(
          $response->unit->Т,
          $numberSpellingResult->unit->instrumental
        );

        $this->assertEquals(
          $response->unit->П,
          $numberSpellingResult->unit->prepositional
        );

        $this->assertNull(
          $numberSpellingResult->unit->prepositional_O
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: unit.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client) {
              $client->spell(5, '');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          496,
          'Не найдено русских слов.',
          5
        );

        $this->assertThrows(
          ArgumentNotRussianException::class,
          function () use ($client) {
              $client->spell(5, 'test');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client) {
              $client->spell(5, 'рубль');
          });
    }

    public function testSpellOrdinal(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = (object)[
          'n' => (object)[
            'И' => 'пятое',
            'Р' => 'пятого',
            'Д' => 'пятому',
            'В' => 'пятое',
            'Т' => 'пятым',
            'П' => 'пятом',
          ],
          'unit' => (object)[
            'И' => 'колесо',
            'Р' => 'колеса',
            'Д' => 'колесу',
            'В' => 'колесо',
            'Т' => 'колесом',
            'П' => 'колесе',
          ],
        ];

        $client->communicator->response = $response;

        $client->spellOrdinal(5, 'колесо');

        $this->assertEquals(
          '/russian/spell-ordinal',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          5,
          $client->communicator->lastParams['n']
        );

        $this->assertEquals(
          'колесо',
          $client->communicator->lastParams['unit']
        );

        $this->assertEquals(
          Communicator::METHOD_GET,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $numberSpellingResult = $client->spellOrdinal(5, 'колесо');

        $this->assertEquals(
          $response->n->Р,
          $numberSpellingResult->n->genitive
        );

        $this->assertEquals(
          $response->n->Д,
          $numberSpellingResult->n->dative
        );

        $this->assertEquals(
          $response->n->В,
          $numberSpellingResult->n->accusative
        );

        $this->assertEquals(
          $response->n->Т,
          $numberSpellingResult->n->instrumental
        );

        $this->assertEquals(
          $response->n->П,
          $numberSpellingResult->n->prepositional
        );

        $this->assertNull(
          $numberSpellingResult->n->prepositional_O
        );

        $this->assertEquals(
          $response->unit->Р,
          $numberSpellingResult->unit->genitive
        );

        $this->assertEquals(
          $response->unit->Д,
          $numberSpellingResult->unit->dative
        );

        $this->assertEquals(
          $response->unit->В,
          $numberSpellingResult->unit->accusative
        );

        $this->assertEquals(
          $response->unit->Т,
          $numberSpellingResult->unit->instrumental
        );

        $this->assertEquals(
          $response->unit->П,
          $numberSpellingResult->unit->prepositional
        );

        $this->assertNull(
          $numberSpellingResult->unit->prepositional_O
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: unit.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client) {
              $client->spellOrdinal(5, '');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          496,
          'Не найдено русских слов.',
          5
        );

        $this->assertThrows(
          ArgumentNotRussianException::class,
          function () use ($client) {
              $client->spellOrdinal(5, 'test');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client) {
              $client->spellOrdinal(5, 'колесо');
          });
    }

    public function testSpellDate(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = (object)[
          'И' => 'первое января две тысячи двадцать первого года',
          'Р' => 'первого января две тысячи двадцать первого года',
          'Д' => 'первому января две тысячи двадцать первого года',
          'В' => 'первое января две тысячи двадцать первого года',
          'Т' => 'первым января две тысячи двадцать первого года',
          'П' => 'первом января две тысячи двадцать первого года',
        ];

        $client->communicator->response = $response;

        $date = new DateTime('2021-01-01');
        $client->spellDate($date);

        $this->assertEquals(
          '/russian/spell-date',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          $date->format('2021-01-01'),
          $client->communicator->lastParams['date']
        );

        $this->assertEquals(
          Communicator::METHOD_GET,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $dateSpellingResult = $client->spellDate($date);

        $this->assertEquals(
          $response->И,
          $dateSpellingResult->nominative
        );

        $this->assertEquals(
          $response->Р,
          $dateSpellingResult->genitive
        );

        $this->assertEquals(
          $response->Д,
          $dateSpellingResult->dative
        );

        $this->assertEquals(
          $response->В,
          $dateSpellingResult->accusative
        );

        $this->assertEquals(
          $response->Т,
          $dateSpellingResult->instrumental
        );

        $this->assertEquals(
          $response->П,
          $dateSpellingResult->prepositional
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: date.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client, $date) {
              $client->spellDate($date);
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client, $date) {
              $client->spellDate($date);
          });
    }

    public function testAdjectiveGenders(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = (object)[
          'feminine' => 'уважаемая',
          'neuter' => 'уважаемое',
          'plural' => 'уважаемые',
        ];

        $client->communicator->response = $response;

        $client->adjectiveGenders('уважаемый');

        $this->assertEquals(
          '/russian/genders',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          'уважаемый',
          $client->communicator->lastParams['s']
        );

        $this->assertEquals(
          Communicator::METHOD_GET,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $adjectiveGenders = $client->adjectiveGenders('уважаемый');

        $this->assertEquals(
          $response->feminine,
          $adjectiveGenders->feminine
        );

        $this->assertEquals(
          $response->neuter,
          $adjectiveGenders->neuter
        );

        $this->assertEquals(
          $response->plural,
          $adjectiveGenders->plural
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: s.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client) {
              $client->adjectiveGenders('уважаемый');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client) {
              $client->adjectiveGenders('уважаемый');
          });
    }

    public function testAdjectivize(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = ['ростовский'];

        $client->communicator->response = $response;

        $client->adjectivize('Ростов');

        $this->assertEquals(
          '/russian/adjectivize',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          'Ростов',
          $client->communicator->lastParams['s']
        );

        $this->assertEquals(
          Communicator::METHOD_GET,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $result = $client->adjectivize('Ростов');

        $this->assertEquals(
          $response,
          $result
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Не указан обязательный параметр: s.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client) {
              $client->adjectivize('Ростов');
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client) {
              $client->adjectivize('Ростов');
          });
    }

    public function testAddStressMarks(): void
    {
        $client = new class extends RussianClient {
            public Communicator $communicator;

            public function __construct()
            {
                $communicator = new MockCommunicator();
                parent::__construct($communicator);
            }
        };

        // should use the correct parameters
        $response = 'Бе́лки|Белки́ пита́ются бе́лками|белка́ми';
        $text = 'Белки питаются белками';

        $client->communicator->response = $response;

        $client->addStressMarks($text);

        $this->assertEquals(
          '/russian/addstressmarks',
          $client->communicator->lastPath
        );

        $this->assertEquals(
          $text,
          $client->communicator->lastParams[Communicator::CONTENT_BODY_KEY]
        );

        $this->assertEquals(
          Communicator::METHOD_POST,
          $client->communicator->lastHttpMethod
        );

        // should return spelling number
        $result = $client->addStressMarks($text);

        $this->assertEquals(
          $response,
          $result
        );

        // should throw Exception
        $client->communicator->exception = new InvalidServerResponseException(
          400,
          'Текст должен передаваться в теле запроса.',
          6
        );

        $this->assertThrows(
          ArgumentEmptyException::class,
          function () use ($client, $text) {
              $client->addStressMarks($text);
          });

        $client->communicator->exception = new InvalidServerResponseException(
          500,
          'Ошибка сервера.',
          11
        );

        $this->assertThrows(
          InvalidServerResponseException::class,
          function () use ($client, $text) {
              $client->addStressMarks($text);
          });
    }
}
