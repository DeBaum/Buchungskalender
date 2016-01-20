<?php

namespace extras;


/**
 * Represents the configuration of an "extra".
 *
 * @package extras
 */
abstract class BaseExtra implements SerializableExtra
{
    /** @var int Extra identifier */
    public $id;

    /** @var string Display title */
    public $title;

    /** @var int ExtraType identifier */
    public $typeId;

    /** @var string Default value */
    public $default;

    /**
     * BaseExtra constructor.
     *
     * @param int $id Extra identifier
     * @param string $title Display title
     * @param int $typeId ExtraType identifier
     */
    protected function __construct($id, $title, $typeId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->typeId = $typeId;
    }

    /**
     * Creates the field-configuration for this Extra that
     * is used by the client to display the form.
     *
     * @return mixed
     */
    public function createDisplayConfig()
    {
        return $this->createDisplayForm();
    }

    protected abstract function createDisplayForm();
}

interface SerializableExtra
{
    public static function fromDb($id, $title, $typeId, $config);
}
