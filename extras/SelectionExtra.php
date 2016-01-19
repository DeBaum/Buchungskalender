<?php

namespace extras;

use extras\forms\InputFieldType;
use extras\forms\SelectField;


/**
 * Represents a selection.
 *
 * @package extras
 */
class SelectionExtra extends BaseExtra
{
    private $values = [];
    private $config;

    public function __construct($id, $title, $typeId, $config)
    {
        parent::__construct($id, $title, $typeId);
        foreach ($config->values as $value) {
            array_push($this->values, new SelectOption($value->id, $value->title));
        }
        $this->config = $config;
    }

    protected function createDisplayForm()
    {
        return new SelectField($this, InputFieldType::Select, $this->values);
    }

    /**
     * @param int $id Extra identifier
     * @param string $title Extra title
     * @param int $typeId ExtraType identifier
     * @param mixed $config ExtraType configuration
     * @return SelectionExtra
     */
    public static function fromDb($id, $title, $typeId, $config)
    {
        return new SelectionExtra($id, $title, $typeId, $config);
    }
}

/**
 * Represents a single option.
 *
 * @package extras\forms
 */
class SelectOption
{
    /**
     * @var int Option identifier
     */
    public $id;

    /**
     * @var string Display title
     */
    public $title;

    /**
     * SelectOption constructor.
     *
     * @param int $id Option identifier
     * @param string $title Display title
     */
    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }
}
