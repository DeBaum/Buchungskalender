<?php

namespace extras;


abstract class BaseExtra
{
    /**
     * @var int Extra identifier
     */
    public $id;
    public $title;
    public $typeId;
    public $default;

    /**
     * BaseExtra constructor.
     * @param int $id Extra identifier
     * @param string $title Extra title
     * @param int $typeId ExtraType identifier
     */
    protected function __construct($id, $title, $typeId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->typeId = $typeId;
    }

    public function createDisplayConfig()
    {
        return $this->createDisplayForm();
    }

    protected abstract function createDisplayForm();

    /**
     * @param int $id Extra identifier
     * @param string $title Extra title
     * @param int $typeId ExtraType identifier
     * @param mixed $config ExtraType configuration
     * @return BaseExtra
     */
    public abstract static function fromDb($id, $title, $typeId, $config);
}