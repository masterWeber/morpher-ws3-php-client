<?php


namespace Morpher\Russian;


use stdClass;

class CorrectionEntry
{
    public CorrectionForms $singular;
    public ?CorrectionForms $plural = null;
    public ?string $gender = null;

    public function __construct(stdClass $props)
    {
        $this->singular = new CorrectionForms($props->singular);

        if ($props->plural) {
            $this->plural = new CorrectionForms($props->plural);
        }

        if ($props->gender) {
            $this->gender = $props->gender;
        }
    }

    public function toNameValueArray(): array
    {
        // Singular
        $nameValueArray['И'] = $this->singular->nominative;
        $nameValueArray['Р'] = $this->singular->genitive;
        $nameValueArray['Д'] = $this->singular->dative;
        $nameValueArray['В'] = $this->singular->accusative;
        $nameValueArray['Т'] = $this->singular->instrumental;
        $nameValueArray['П'] = $this->singular->prepositional;
        $nameValueArray['М'] = $this->singular->locative;

        // Plural
        if ($this->plural) {
            $nameValueArray['М_И'] = $this->plural->nominative;
            $nameValueArray['М_Р'] = $this->plural->genitive;
            $nameValueArray['М_Д'] = $this->plural->dative;
            $nameValueArray['М_В'] = $this->plural->accusative;
            $nameValueArray['М_Т'] = $this->plural->instrumental;
            $nameValueArray['М_П'] = $this->plural->prepositional;
            $nameValueArray['М_М'] = $this->plural->locative;
        }

        return $nameValueArray;
    }
}