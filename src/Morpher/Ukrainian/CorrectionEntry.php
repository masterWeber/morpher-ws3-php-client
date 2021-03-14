<?php


namespace Morpher\Ukrainian;


use stdClass;

class CorrectionEntry
{
    public ?CorrectionForms $singular = null;
    public ?string $gender = null;

    public function __construct(?stdClass $props = null)
    {
        if (isset($props->singular)) {
            $this->singular = new CorrectionForms($props->singular);
        }

        if (isset($props->gender)) {
            $this->gender = $props->gender;
        }
    }

    public function toNameValueArray(): array
    {
        $nameValueArray['Н'] = $this->singular->nominative;
        $nameValueArray['Р'] = $this->singular->genitive;
        $nameValueArray['Д'] = $this->singular->dative;
        $nameValueArray['З'] = $this->singular->accusative;
        $nameValueArray['О'] = $this->singular->instrumental;
        $nameValueArray['М'] = $this->singular->prepositional;
        $nameValueArray['К'] = $this->singular->vocative;

        return $nameValueArray;
    }
}