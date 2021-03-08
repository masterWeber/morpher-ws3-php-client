<?php


namespace Morpher\Russian;


use stdClass;

class FullName
{
    public string $name;
    public string $surname;
    public string $patronymic;

    public function __construct(stdClass $props)
    {
        $this->name = $props->И;
        $this->surname = $props->Ф;
        $this->patronymic = $props->О;
    }
}