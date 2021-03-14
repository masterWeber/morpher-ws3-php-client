<?php

namespace Morpher\Russian;

use Morpher\Russian\CorrectionForms;
use PHPUnit\Framework\TestCase;
use stdClass;

class CorrectionFormsTest extends TestCase
{
    public function testConstruct()
    {
        $props = new stdClass();
        $props->И = 'Тест';
        $props->Р = 'Теста';
        $props->Д = 'Тесту';
        $props->В = 'Тест';
        $props->Т = 'Тестом';
        $props->П = 'Тесте';
        $props->М = 'Тесте';

        $correctionForms = new CorrectionForms($props);

        $this->assertEquals(
          $props->И,
          $correctionForms->nominative
        );

        $this->assertEquals(
          $props->Р,
          $correctionForms->genitive
        );

        $this->assertEquals(
          $props->Д,
          $correctionForms->dative
        );

        $this->assertEquals(
          $props->В,
          $correctionForms->accusative
        );

        $this->assertEquals(
          $props->Т,
          $correctionForms->instrumental
        );

        $this->assertEquals(
          $props->П,
          $correctionForms->prepositional
        );

        $this->assertEquals(
          $props->М,
          $correctionForms->locative
        );
    }
}
