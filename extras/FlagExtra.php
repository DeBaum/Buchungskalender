<?php

namespace extras;

include_once __DIR__ . '/forms/fields/CheckboxField.php';

use extras\forms\CheckboxField;
use extras\forms\InputFieldType;


/**
 * Represents a true/false option.
 *
 * @package extras
 */
class FlagExtra extends BaseExtra
{
    public function __construct($id, $title, $typeId, $config)
    {
        parent::__construct($id, $title, $typeId);
        $this->default = $config->default;
        if ($this->default !== 0 && $this->default !== 1) {
            // should be 0 or 1
            throw new \InvalidArgumentException("flag default must be 0 or 1");
        }
    }

    protected function createDisplayForm()
    {
        return new CheckboxField($this, InputFieldType::Checkbox);
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
        return new FlagExtra($id, $title, $typeId, $config);
    }
}
