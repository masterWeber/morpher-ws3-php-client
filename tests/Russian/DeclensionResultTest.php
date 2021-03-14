<?php

namespace Russian;

use Morpher\Russian\DeclensionResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeclensionResultTest extends TestCase
{
    public function testConstruct(): void
    {
        $props = new stdClass();
        $props->Р = 'Соединенного королевства';
        $props->Д = 'Соединенному королевству';
        $props->В = 'Соединенное королевство';
        $props->Т = 'Соединенным королевством';
        $props->П = 'Соединенном королевстве';
        $props->П_о = 'о Соединенном королевстве';
        $props->род = 'Средний';
        $props->где = 'в Соединенном королевстве';
        $props->куда = 'в Соединенное королевство';
        $props->откуда = 'из Соединенного королевства';

        $plural = new stdClass();
        $plural->И = 'Соединенные королевства';
        $plural->Р = 'Соединенных королевств';
        $plural->Д = 'Соединенным королевствам';
        $plural->В = 'Соединенные королевства';
        $plural->Т = 'Соединенными королевствами';
        $plural->П = 'Соединенных королевствах';
        $plural->П_о = 'о Соединенных королевствах';

        $props->множественное = $plural;

        $declensionResult = new DeclensionResult($props);

        $this->assertNull(
          $declensionResult->nominative
        );

        $this->assertEquals(
          $props->Р,
          $declensionResult->genitive
        );

        $this->assertEquals(
          $props->Д,
          $declensionResult->dative
        );

        $this->assertEquals(
          $props->В,
          $declensionResult->accusative
        );

        $this->assertEquals(
          $props->Т,
          $declensionResult->instrumental
        );

        $this->assertEquals(
          $props->П,
          $declensionResult->prepositional
        );

        $this->assertEquals(
          $props->П_о,
          $declensionResult->prepositional_O
        );

        $this->assertNull(
          $declensionResult->fullName
        );

        $props = new stdClass();
        $props->Р = 'Слепова Сергея Николаевича';
        $props->Д = 'Слепову Сергею Николаевичу';
        $props->В = 'Слепова Сергея Николаевича';
        $props->Т = 'Слеповым Сергеем Николаевичем';
        $props->П = 'Слепове Сергее Николаевиче';

        $fullName = new stdClass();
        $fullName->Ф = 'Слепов';
        $fullName->И = 'Сергей';
        $fullName->О = 'Николаевич';

        $props->ФИО = $fullName;

        $declensionResult = new DeclensionResult($props);

        $this->assertEquals(
          $props->Р,
          $declensionResult->genitive
        );

        $this->assertEquals(
          $props->Д,
          $declensionResult->dative
        );

        $this->assertEquals(
          $props->В,
          $declensionResult->accusative
        );

        $this->assertEquals(
          $props->Т,
          $declensionResult->instrumental
        );

        $this->assertEquals(
          $props->П,
          $declensionResult->prepositional
        );

        $this->assertEquals(
          $props->ФИО->И,
          $declensionResult->fullName->name
        );

        $this->assertEquals(
          $props->ФИО->Ф,
          $declensionResult->fullName->surname
        );

        $this->assertEquals(
          $props->ФИО->О,
          $declensionResult->fullName->patronymic
        );
    }
}
