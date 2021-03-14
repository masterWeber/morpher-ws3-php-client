<?php


namespace Morpher\Ukrainian;


use stdClass;

class DeclensionResult extends DeclensionForms
{
    public string $gender = '';

    public function __construct(stdClass $props)
    {
        parent::__construct($props);

        if (isset($props->рід)) {
            $this->gender = $props->рід;
        }
    }
}