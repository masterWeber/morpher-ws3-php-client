<?php


namespace Morpher\Qazaq;


use stdClass;

class SameNumberForms extends DeclensionForms
{
    public DeclensionForms $firstPerson;
    public DeclensionForms $secondPerson;
    public DeclensionForms $secondPersonRespectful;
    public DeclensionForms $thirdPerson;
    public DeclensionForms $firstPersonPlural;
    public DeclensionForms $secondPersonPlural;
    public DeclensionForms $secondPersonRespectfulPlural;
    public DeclensionForms $thirdPersonPlural;

    public function __construct(stdClass $props)
    {
        parent::__construct($props);

        $this->firstPerson = new DeclensionForms($props->менің);
        $this->secondPerson = new DeclensionForms($props->сенің);
        $this->secondPersonRespectful = new DeclensionForms($props->сіздің);
        $this->thirdPerson = new DeclensionForms($props->оның);
        $this->firstPersonPlural = new DeclensionForms($props->біздің);
        $this->secondPersonPlural = new DeclensionForms($props->сендердің);
        $this->secondPersonRespectfulPlural = new DeclensionForms($props->сіздердің);
        $this->thirdPersonPlural = new DeclensionForms($props->олардың);
    }
}