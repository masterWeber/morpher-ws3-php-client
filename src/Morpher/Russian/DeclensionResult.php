<?php


namespace Morpher\Russian;


use stdClass;

class DeclensionResult extends DeclensionForms
{
    public string $gender = '';
    public ?DeclensionForms $plural = null;
    public string $where = '';
    public string $locative = '';
    public string $where_to = '';
    public string $where_from = '';
    public string $whence = '';
    public ?FullName $fullName = null;

    public function __construct(stdClass $props)
    {
        parent::__construct($props);

        if (isset($props->род)) {
            $this->gender = $props->род;
        }

        if (isset($props->множественное)) {
            $this->plural = new DeclensionForms($props->множественное);
        }

        if (isset($props->где)) {
            $this->where = $props->где;
            $this->locative = $this->where;
        }

        if (isset($props->куда)) {
            $this->where_to = $props->куда;
        }

        if (isset($props->откуда)) {
            $this->where_from = $props->откуда;
            $this->whence = $this->where_from;
        }

        if (isset($props->ФИО)) {
            $this->fullName = new FullName($props->ФИО);
        }
    }
}