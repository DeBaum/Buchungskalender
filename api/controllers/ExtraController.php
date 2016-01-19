<?php

namespace Bookings\Controller;


use Bookings\Models\ExtraWithType;
use Exception;
use extras\BaseExtra;
use extras\ExtraHandler;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

class ExtraController extends BaseController
{

    /**
     * @var ExtraHandler
     */
    private $extraHandler;

    /**
     * ExtraController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->extraHandler = ExtraHandler::getInstance();
    }

    /**
     * @param \Bookings\Models\Resource $resource
     * @return BaseExtra[]
     */
    public function getExtrasForResource(
        /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
        \Bookings\Models\Resource $resource)
    {
        $extraWithType = $this->getExtrasForCategory($resource->category_id);
        return $extraWithType;
    }

    public function getExtrasForCategory($categoryId)
    {
        $extras = [];
        $rows = $this->fetchAll("SELECT extra.*, type.field_class, type.title AS field_title
                                 FROM `bookings_extra` extra INNER JOIN bookings_field_type type ON type.id = extra.type_id
                                 WHERE category_id = :d", [$categoryId]);
        foreach ($rows as $row) {
            $extraWithType = ExtraWithType::fromDb($row);
            $extra = $this->resolveExtra($extraWithType);
            if ($extra == null) {
                throw new Exception("could not resolve extra " . $extraWithType->id . " (" . $extraWithType->type->field_class . ")");
            }
            array_push($extras, $extra);
        }
        return $extras;
    }

    private function resolveExtra(ExtraWithType $extra)
    {
        $extra = $this->extraHandler->getExtra($extra->id, $extra->title, $extra->type_id, $extra->type->field_class, $extra->config);
        return $extra;
    }
}