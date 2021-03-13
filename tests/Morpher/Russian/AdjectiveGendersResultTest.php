<?php

namespace Morpher\Russian;

use Morpher\Russian\AdjectiveGendersResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class AdjectiveGendersResultTest extends TestCase
{
    public function testConstruct(): void
    {
        $data = new stdClass();
        $data->feminine = 'уважаемая';
        $data->neuter = 'уважаемое';
        $data->plural = 'уважаемые';

        $adjectiveGendersResult = new AdjectiveGendersResult($data);

        $this->assertEquals(
          $data->feminine,
          $adjectiveGendersResult->feminine
        );

        $this->assertEquals(
          $data->neuter,
          $adjectiveGendersResult->neuter
        );

        $this->assertEquals(
          $data->plural,
          $adjectiveGendersResult->plural
        );
    }
}
