<?php

namespace Morpher\Russian;

use Morpher\Russian\NumberSpellingResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class NumberSpellingResultTest extends TestCase
{
    public function testConstruct(): void
    {
        $props = new stdClass();

        $n = new stdClass();
        $n->И = 'двести тридцать пять';
        $n->Р = 'двухсот тридцати пяти';
        $n->Д = 'двумстам тридцати пяти';
        $n->В = 'двести тридцать пять';
        $n->Т = 'двумястами тридцатью пятью';
        $n->П = 'двухстах тридцати пяти';

        $props->n = $n;

        $unit = new stdClass();
        $unit->И = 'рублей';
        $unit->Р = 'рублей';
        $unit->Д = 'рублям';
        $unit->В = 'рублей';
        $unit->Т = 'рублями';
        $unit->П = 'рублях';

        $props->unit = $unit;

        $numberSpellingResult = new NumberSpellingResult($props);

        $this->assertEquals(
          $props->n->И,
          $numberSpellingResult->n->nominative
        );

        $this->assertEquals(
          $props->n->Р,
          $numberSpellingResult->n->genitive
        );

        $this->assertEquals(
          $props->n->Д,
          $numberSpellingResult->n->dative
        );

        $this->assertEquals(
          $props->n->В,
          $numberSpellingResult->n->accusative
        );

        $this->assertEquals(
          $props->n->Т,
          $numberSpellingResult->n->instrumental
        );

        $this->assertEquals(
          $props->n->П,
          $numberSpellingResult->n->prepositional
        );

        $this->assertEquals(
          $props->unit->И,
          $numberSpellingResult->unit->nominative
        );

        $this->assertEquals(
          $props->unit->Р,
          $numberSpellingResult->unit->genitive
        );

        $this->assertEquals(
          $props->unit->Д,
          $numberSpellingResult->unit->dative
        );

        $this->assertEquals(
          $props->unit->В,
          $numberSpellingResult->unit->accusative
        );

        $this->assertEquals(
          $props->unit->Т,
          $numberSpellingResult->unit->instrumental
        );

        $this->assertEquals(
          $props->unit->П,
          $numberSpellingResult->unit->prepositional
        );
    }
}
