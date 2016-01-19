<?php

namespace extras;


class ExtraHandler
{
    // TODO: implement instance cache

    private static $instance;

    public function getExtra($id, $title, $typeId, $fieldClass, $config)
    {
        /**
         * @var BaseExtra $instance
         */
        $instance = "\\extras\\$fieldClass";
        return $instance::fromDb($id, $title, $typeId, $config);
    }

    public static function getInstance()
    {
        if (self::$instance !== null) {
            return self::$instance;
        } else {
            self::$instance = new ExtraHandler();
            return self::$instance;
        }
    }
}