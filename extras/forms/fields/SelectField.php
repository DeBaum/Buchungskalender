<?php

namespace extras\forms;

use extras\SelectOption;

/**
 * Represents a selectable option.
 *
 * @package extras\forms
 */
class SelectField extends BaseField
{
    /**
     * @var SelectOption[] Options for this field
     */
    public $values;

    /**
     * @var bool|null Marks the default option
     */
    public $default = null;

    /**
     * SelectField constructor.
     *
     * @param int $id Extra identifier
     * @param string $type Type
     * @param SelectOption[] $values Options for this field
     * @param bool|null $default Marks the default option
     */
    public function __construct($id, $type, $values, $default = null)
    {
        parent::__construct($id, $type);
        $this->values = $values;
        $this->default = $default;
    }
}
