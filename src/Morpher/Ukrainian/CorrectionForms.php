<?php


namespace Morpher\Ukrainian;


use stdClass;

class CorrectionForms
{
    public string $nominative = '';
    public string $genitive = '';
    public string $dative = '';
    public string $accusative = '';
    public string $instrumental = '';
    public string $prepositional = '';
    public string $vocative = '';

    public function __construct(?stdClass $props = null)
    {
        if (isset($props->Н)) {
            $this->nominative = $props->Н;
        }

        if (isset($props->Р)) {
            $this->genitive = $props->Р;
        }

        if (isset($props->Д)) {
            $this->dative = $props->Д;
        }

        if (isset($props->З)) {
            $this->accusative = $props->З;
        }

        if (isset($props->О)) {
            $this->instrumental = $props->О;
        }

        if (isset($props->М)) {
            $this->prepositional = $props->М;
        }

        if (isset($props->К)) {
            $this->vocative = $props->К;
        }
    }
}