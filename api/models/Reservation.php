<?php

namespace Bookings\Models;


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

    public function __construct($id, $resourceId, $timeFrom, $timeTo, $creatorId, $creationTime, $eventFrom, $eventTo, $quantity)
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
    }

    public static function fromJson($json)
    {
        return Reservation::fromDb($json);
    }

    public static function fromDb($row)
    {
        if ($row == null) {
            return null;
        }

        return new Reservation($row->id, $row->resource_id, $row->time_from, $row->time_to, $row->creator_id,
            $row->creation - time, $row->event_from, $row->event_to, $row->quantity);
    }
}