<?php


namespace Morpher\Ukrainian;


use stdClass;

class NumberSpellingResult
{
    public DeclensionForms $n;
    public DeclensionForms $unit;

    public function __construct(stdClass $props)
    {
        $this->n = new DeclensionForms($props->n);
        $this->unit = new DeclensionForms($props->unit);
    }
}