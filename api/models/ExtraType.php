<?php

namespace Bookings\Models;


class ExtraType extends BaseModel
{
    /**
     * Unique identifier
     * If zero the object was not stored before
     * @var int
     */
    public $id;
    public $field_class;
    public $title;

    public function __construct($id, $field_class, $title)
    {
        if ($id === null) {
            $id = 0;
        }
        if (!$this->isInt($id, 0)) {
            throw new \InvalidArgumentException("extraType id must be an integer");
        }
        if (!$this->isString($title)) {
            throw new \InvalidArgumentException("extraType title must not be empty");
        }
        if (!$this->isString($field_class)) {
            throw new \InvalidArgumentException("extraType field_class must not be empty");
        }

        $this->id = $id;
        $this->title = $title;
        $this->field_class = $field_class;
    }

    public static function fromJson($json)
    {
        return Resource::fromDb($json);
    }

    public static function fromDb($row)
    {
        if ($row == null) {
            return null;
        }

        return new ExtraType(intval($row->id), intval($row->field_class), $row->title);
    }
}