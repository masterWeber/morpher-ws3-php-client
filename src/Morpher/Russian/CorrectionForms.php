<?php


namespace Morpher\Russian;


use stdClass;

class CorrectionForms
{
    public string $locative = '';
    public string $prepositional = '';
    public string $instrumental = '';
    public string $accusative = '';
    public string $dative = '';
    public string $genitive = '';
    public string $nominative = '';

    public function __construct(stdClass $props)
    {
        if (isset($props->И)) {
            $this->nominative = $props->И;
        }

        if (isset($props->Р)) {
            $this->genitive = $props->Р;
        }

        if (isset($props->Д)) {
            $this->dative = $props->Д;
        }

        if (isset($props->В)) {
            $this->accusative = $props->В;
        }

        if (isset($props->Т)) {
            $this->instrumental = $props->Т;
        }

        if (isset($props->П)) {
            $this->prepositional = $props->П;
        }

        if (isset($props->М)) {
            $this->locative = $props->М;
        }
    }
}