<?php

namespace extras\forms;


use extras\BaseExtra;
use help\BasicEnum;

/**
 * Represents a "simple" input field
 *
 * @package extras\forms
 */
abstract class BaseField
{
    /** @var int Extra identifier */
    public $id;

    /** @var string Type */
    public $type;

    /** @var string Display title */
    public $title;

    /**
     * BaseField constructor.
     *
     * @param BaseExtra $extra Extra
     * @param string $type Form type
     */
    public function __construct(BaseExtra $extra, $type)
    {
        if (!InputFieldType::isValidValue($type))
            throw new \InvalidArgumentException("unknown field type: `$type`");
        $this->id = $extra->id;
        $this->type = $type;
        $this->title = $extra->title;
    }
}

/**
 * Enum for form-types supported by the client.
 *
 * @package extras\forms
 */
abstract class InputFieldType extends BasicEnum
{
    const Select = "select";
    const Checkbox = "check";
    const Textarea = "textarea";
}
