<?php

namespace extras\forms;


use extras\BaseExtra;
use extras\SelectOption;

/**
 * Represents a selectable option.
 *
 * @package extras\forms
 */
class SelectField extends BaseField
{
    /** @var SelectOption[] Options for this field */
    public $values;

    /** @var bool|null Marks the default option */
    public $default = null;

    /**
     * SelectField constructor.
     *
     * @param BaseExtra $extra Extra
     * @param string $type Type
     * @param SelectOption[] $values Options for this field
     */
    public function __construct(BaseExtra $extra, $type, $values)
    {
        parent::__construct($extra, $type);
        $this->values = $values;
        $this->default = $extra->default;
    }
}
