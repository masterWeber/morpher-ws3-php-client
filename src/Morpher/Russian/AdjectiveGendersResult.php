<?php


namespace Morpher\Russian;


use stdClass;

class AdjectiveGendersResult
{
    public string $feminine;
    public string $neuter;
    public string $plural;

    public function __construct(stdClass $props)
    {
        $this->feminine = $props->feminine;
        $this->neuter = $props->neuter;
        $this->plural = $props->plural;
    }
}