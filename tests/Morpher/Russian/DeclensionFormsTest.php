<?php

namespace Morpher\Russian;

use Morpher\Russian\DeclensionForms;
use PHPUnit\Framework\TestCase;
use stdClass;

class DeclensionFormsTest extends TestCase
{

    public function testConstruct(): void
    {
        $props = new stdClass();
        $props->Р = 'Теста';
        $props->Д = 'Тесту';
        $props->В = 'Тест';
        $props->Т = 'Тестом';
        $props->П = 'Тесте';

        $declensionForms = new DeclensionForms($props);

        $this->assertNull(
          $declensionForms->nominative
        );

        $this->assertEquals(
          $props->Р,
          $declensionForms->genitive
        );

        $this->assertEquals(
          $props->Д,
          $declensionForms->dative
        );

        $this->assertEquals(
          $props->В,
          $declensionForms->accusative
        );

        $this->assertEquals(
          $props->Т,
          $declensionForms->instrumental
        );

        $this->assertEquals(
          $props->П,
          $declensionForms->prepositional
        );

        $this->assertNull(
          $declensionForms->prepositional_O
        );

        $props->И = 'Тест';
        $props->П_о = 'О тесте';

        $declensionForms = new DeclensionForms($props);

        $this->assertEquals(
          $props->И,
          $declensionForms->nominative
        );

        $this->assertEquals(
          $props->П_о,
          $declensionForms->prepositional_O
        );
    }
}
