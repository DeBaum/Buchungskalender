<?php

namespace Bookings\Models;


class Extra extends BaseModel
{
    /**
     * Unique identifier
     * If zero the object was not stored before
     * @var int
     */
    public $id;
    public $category_id;
    public $title;
    public $type_id;
    public $config;

    /**
     * Extra constructor.
     * @param int $id Extra identifier
     * @param int $categoryId Category identifier
     * @param string $title Title
     * @param int $typeId Type identifier
     * @param string $config Type specific config
     */
    public function __construct($id, $categoryId, $title, $typeId, $config)
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
        $this->type_id = $typeId;
        $this->config = $config;
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