<?php

namespace Bookings\Models;


class ExtraWithType extends Extra
{
    /**
     * @var ExtraType Type
     */
    public $type;

    public function __construct($id, $categoryId, $title, ExtraType $type, $config)
    {
        parent::__construct($id, $categoryId, $title, $type->id, $config);
        $this->type = $type;
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

        $type = new ExtraType(intval($row->type_id), $row->field_class, $row->field_title);
        return new ExtraWithType(intval($row->id), intval($row->category_id), $row->title, $type, json_decode($row->config));
    }
}