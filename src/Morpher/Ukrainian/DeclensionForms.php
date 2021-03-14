<?php


namespace Morpher\Ukrainian;


use stdClass;

class DeclensionForms
{
    public ?string $nominative = null;
    public string $genitive;
    public string $dative;
    public string $accusative;
    public string $instrumental;
    public string $prepositional;
    public string $vocative;

    public function __construct(stdClass $props)
    {
        if (isset($props->Н)) {
            $this->nominative = $props->Н;
        }

        $this->genitive = $props->Р;
        $this->dative = $props->Д;
        $this->accusative = $props->З;
        $this->instrumental = $props->О;
        $this->prepositional = $props->М;
        $this->vocative = $props->К;
    }
}