<?php

namespace Bookings\Models;


include_once __DIR__ . "/ReservationExtra.php";

use api\models\ReservationExtra;

class Reservation extends BaseModel
{
    /**
     * Unique identifier
     * If zero the object was not stored before
     * @var int
     */
    public $id;
    public $resource_id;
    public $time_from;
    public $time_to;
    public $creator_id;
    public $creation_time;
    public $event_from;
    public $event_to;
    public $quantity;

    /**
     * @var ReservationExtra[] Extras
     */
    public $extras;

    /**
     * Reservation constructor.
     * @param int $id
     * @param int $resourceId
     * @param string $timeFrom
     * @param string $timeTo
     * @param int $creatorId
     * @param string $creationTime
     * @param string $eventFrom
     * @param string $eventTo
     * @param int $quantity
     * @param ReservationExtra[] $extras
     */
    public function __construct($id, $resourceId, $timeFrom, $timeTo, $creatorId, $creationTime, $eventFrom, $eventTo, $quantity, $extras)
    {
        if ($id === null) {
            $id = 0;
        }
        $isNew = $this->isInt($id, 0);
        if (!$isNew) {
            throw new \InvalidArgumentException("id must be an integer");
        }
        if (!$this->isInt($resourceId)) {
            throw new \InvalidArgumentException("missing resource_id");
        }
        if (!$this->isInt($creatorId)) {
            throw new \InvalidArgumentException("missing creator_id");
        }
        if (!$this->isInt($quantity, 1)) {
            throw new \InvalidArgumentException("missing or invalid quantity");
        }
        if ($extras !== null && !is_array($extras)) {
            throw new \InvalidArgumentException("reservation extras must be an array");
        }

        if ($isNew) {
            $creationTime = null;
        } else {
            if (!$this->isDate($timeFrom) || !$this->isDate($timeTo)) {
                throw new \InvalidArgumentException("invalid dates for time_from or time_to");
            }
        }

        $this->id = $id;
        $this->resource_id = $resourceId;
        $this->time_from = $timeFrom;
        $this->time_to = $timeTo;
        $this->creator_id = $creatorId;
        $this->creation_time = $creationTime;
        $this->event_from = $eventFrom;
        $this->event_to = $eventTo;
        $this->quantity = $this->isInt($quantity) ? $quantity : 1;
        $this->extras = $extras;
    }

    public static function fromJson($json)
    {
        $extras = [];
        foreach ($json->extras as $extra) {
            array_push($extras, new ReservationExtra($extra->extra_id, $extra->value));
        }
        return Reservation::fromDb($json, $extras);
    }

    public static function fromDb($row, $extras = null)
    {
        if ($row == null) {
            return null;
        }

        return new Reservation(intval($row->id), intval($row->resource_id), $row->time_from, $row->time_to, intval($row->creator_id),
            $row->creation_time, $row->event_from, $row->event_to, intval($row->quantity), $extras);
    }
}