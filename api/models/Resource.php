<?php

namespace Bookings\Models;


class Resource extends BaseModel
{

    /**
     * Unique identifier
     * If zero the object was not stored before
     * @var int
     */
    public $id;
    public $category_id;
    public $title;
    public $quantity;

    public function __construct($id, $categoryId, $title, $quantity)
    {
        if ($id === null) {
            $id = 0;
        }
        if (!$this->isInt($id, 0)) {
            throw new \InvalidArgumentException("id must be an integer");
        }
        if (!$this->isInt($categoryId)) {
            throw new \InvalidArgumentException("missing category_id");
        }
        if (!$this->isString($title)) {
            throw new \InvalidArgumentException("title must not be empty");
        }

        $this->id = $id;
        $this->title = $title;
        $this->category_id = $categoryId;
        $this->quantity = $this->isInt($quantity) ? $quantity : 1;
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

        return new Resource(intval($row->id), intval($row->category_id), $row->title, intval($row->quantity));
    }
}