<?php

namespace extras\forms;

use extras\BaseExtra;

include_once __DIR__ . '/../fields/BaseField.php';

/**
 * Represents a checkbox.
 *
 * @package extras\forms
 */
class CheckboxField extends BaseField
{
    /**
     * @var bool|null Marks the default option
     */
    public $default = null;

    /**
     * SelectField constructor.
     *
     * @param BaseExtra $extra
     * @param string $type Type
     */
    public function __construct(BaseExtra $extra, $type)
    {
        parent::__construct($extra, $type);
        $this->default = $extra->default;
    }
}
