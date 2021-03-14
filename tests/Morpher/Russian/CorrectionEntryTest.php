<?php

namespace Morpher\Russian;

use PHPUnit\Framework\TestCase;
use stdClass;

class CorrectionEntryTest extends TestCase
{
    public function testConstruct(): void
    {
        $props = $this->correctionEntryProvider();

        $correctionEntry = new CorrectionEntry($props);

        $this->assertEquals(
          $props->singular->И,
          $correctionEntry->singular->nominative
        );

        $this->assertEquals(
          $props->singular->Р,
          $correctionEntry->singular->genitive
        );

        $this->assertEquals(
          $props->singular->Д,
          $correctionEntry->singular->dative
        );
    }

    public function testToNameValueArray(): void
    {
        $singular = new CorrectionForms();
        $singular->nominative = 'Кошка';
        $singular->dative = 'Пантере';

        $plural = new CorrectionForms();
        $plural->nominative = 'Пантера';

        $correctionEntry = new CorrectionEntry();
        $correctionEntry->gender = 'женский';
        $correctionEntry->singular = $singular;
        $correctionEntry->plural = $plural;

        $data = $this->dataProvider();

        $this->assertEquals(
          $data,
          $correctionEntry->toNameValueArray()
        );
    }

    private function dataProvider(): array
    {
        return [
          'И' => 'Кошка',
          'Р' => '',
          'Д' => 'Пантере',
          'В' => '',
          'Т' => '',
          'П' => '',
          'М' => '',
          'М_И' => 'Пантера',
          'М_Р' => '',
          'М_Д' => '',
          'М_В' => '',
          'М_Т' => '',
          'М_П' => '',
          'М_М' => '',
        ];
    }

    private function correctionEntryProvider(): stdClass
    {
        $singular = new stdClass();
        $singular->И = 'Кошка';
        $singular->Р = 'Пантеры';
        $singular->Д = 'Пантере';

        $entry = new stdClass();
        $entry->singular = $singular;

        return $entry;
    }
}
