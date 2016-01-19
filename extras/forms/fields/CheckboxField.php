<?php

namespace extras\forms;

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
     * @param int $id Extra identifier
     * @param string $type Type
     * @param bool|null $default Marks the default option
     */
    public function __construct($id, $type, $default = null)
    {
        parent::__construct($id, $type);
        $this->default = $default;
    }
}
