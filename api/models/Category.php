<?php

namespace Bookings\Models;


class Category extends BaseModel
{

    /**
     * Unique identifier
     * If zero the object was not stored before
     * @var int
     */
    public $id;
    public $title;

    public function __construct($id, $title)
    {
        if ($id === null) {
            $id = 0;
        }
        if (!$this->isInt($id, 0)) {
            throw new \InvalidArgumentException("id must be an integer");
        }
        if (!$this->isString($title)) {
            throw new \InvalidArgumentException("title must not be empty");
        }

        $this->id = $id;
        $this->title = $title;
    }

    public static function fromDb($row)
    {
        if ($row == null) {
            return null;
        }

        return new Category(intval($row->id), $row->title);
    }

    public static function fromJson($json)
    {
        return self::fromDb($json);
    }
}