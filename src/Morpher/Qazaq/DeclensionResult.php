<?php


namespace Morpher\Qazaq;


use stdClass;

class DeclensionResult extends DeclensionForms
{
    public DeclensionForms $plural;

    public function __construct(stdClass $props)
    {
        parent::__construct($props);

        $this->plural = new SameNumberForms($props->көпше);
    }
}