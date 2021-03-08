<?php


namespace Morpher\Russian;


use stdClass;

class DeclensionForms
{
    public ?string $nominative = null;
    public string $genitive;
    public string $dative;
    public string $accusative;
    public string $instrumental;
    public string $prepositional;
    public ?string $prepositional_O = null;

    public function __construct(stdClass $props)
    {
        if (isset($props->И)) {
            $this->nominative = $props->И;
        }

        $this->genitive = $props->Р;
        $this->dative = $props->Д;
        $this->accusative = $props->В;
        $this->instrumental = $props->Т;
        $this->prepositional = $props->П;

        if (isset($props->П_о)) {
            $this->prepositional_O = $props->П_о;
        }
    }
}