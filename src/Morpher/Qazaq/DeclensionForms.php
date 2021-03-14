<?php


namespace Morpher\Qazaq;


use stdClass;

class DeclensionForms
{
    public ?string $nominative = null;
    public string $genitive;
    public string $dative;
    public string $accusative;
    public string $ablative;
    public string $locative;
    public string $instrumental;

    public function __construct(stdClass $props)
    {
        if (isset($props->А)) {
            $this->nominative = $props->А;
        }

        $this->genitive = $props->І;
        $this->dative = $props->Б;
        $this->accusative = $props->Т;
        $this->ablative = $props->Ш;
        $this->locative = $props->Ж;
        $this->instrumental = $props->К;
    }
}