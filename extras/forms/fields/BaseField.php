<?php

namespace extras\forms;


use help\BasicEnum;

abstract class InputFieldType extends BasicEnum
{
    const Select = "select";
    const Checkbox = "check";
    const Textarea = "textarea";
}

/**
 * Represents a "simple" input field
 *
 * @package extras\forms
 */
abstract class BaseField
{
    /**
     * @var int Extra identifier
     */
    public $id;

    /**
     * @var string Type
     */
    public $type;

    /**
     * @param $id int Extra identifier
     * @param string $type Form type
     */
    public function __construct($id, $type)
    {
        if (!InputFieldType::isValidValue($type))
            throw new \InvalidArgumentException("unknown field type: `$type`");
        $this->id = $id;
        $this->type = $type;
    }
}