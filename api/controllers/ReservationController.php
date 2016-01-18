<?php

namespace Bookings\Controller;


use Bookings\GlobalErrors;
use Bookings\Models\Reservation;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

class ReservationController extends BaseController
{

    public function create($json)
    {
        if ($json == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $reservation = Reservation::fromJson($json);
        if ($reservation->id > 0) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id must be null", null);
        }

        // check resource
        $resourceController = new ResourceController();
        $resource = $resourceController->get($reservation->resource_id);
        if ($resource == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "resource not found", null);
        }

        // check overlapping
        $result = $this->getReservedObjectsByTime($resource->id, $reservation->time_from, $reservation->time_to);
        $reservedQuantity = $result[0]->quantity;
        $maxQuantity = $resource->quantity;

        if ($reservedQuantity + $reservation->quantity > $maxQuantity) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "quantity exceeded (" . ($reservedQuantity + $reservation->quantity) . "/" . $maxQuantity . ")", null);
        }

        $inserted_id = $this->insert(
            "INSERT INTO bookings_reservation(resource_id, time_from, time_to, creator_id, event_from, event_to, quantity) " .
            "VALUES (:d, :s, :s, :d, :s, :s, :d)",
            array(
                $reservation->resource_id,
                $reservation->time_from,
                $reservation->time_to,
                $reservation->creator_id,
                null,
                $reservation->event_to,
                $reservation->quantity
            ));

        if ($inserted_id > 0) {
            returnResult($this->get($inserted_id));
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }

    /**
     * Calculates the quantity of reserved (unavailable) objects
     * within a specific date range
     *
     * @param $resourceId
     * @param $from
     * @param $to
     *
     * @return array|null|object
     */
    public function getReservedObjectsByTime($resourceId, $from, $to)
    {
        return $this->fetchAll("SELECT SUM(quantity) AS quantity FROM bookings_reservation WHERE resource_id = :d AND :s < time_to AND :s > time_from", array(
            $resourceId,
            $from,
            $to
        ));
    }

    public function get($id)
    {
        return Reservation::fromDb($this->fetchOne("SELECT * FROM bookings_reservation WHERE id = :d", $id));
    }

    public function getAll()
    {
        $resourceId = $this->getParam("resource_id");
        if (!$this->isInt($resourceId, 1))
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing resource_id", null);
        $from = $this->getParam("from");
        if (!$this->isDate($from))
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing from parameter", null);
        $to = $this->getParam("to");
        if (!$this->isDate($to))
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing to parameter", null);

        $resourceController = new ResourceController();
        $resource = $resourceController->get($resourceId);
        if ($resource == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "resource not found", null);
        }

        $reservation = array();
        foreach ($this->fetchAll("SELECT * FROM bookings_reservation WHERE resource_id = :d AND :s <= time_to AND :s >= time_from",
            [$resourceId, $from, $to]) as $row) {
            array_push($reservation, Reservation::fromDb($row));
        }

        return $reservation;
    }

    // Helper

    public function update($id, $json)
    {
        if ($json == null || !$this->isInt($id)) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "missing body", null);
        }

        $reservation = Reservation::fromJson($json);
        if ($reservation->id !== $id) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "id not matching", null);
        }

        $existingReservation = $this->get($id);
        if ($existingReservation == null) {
            return returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }

        // check resource
        $resourceController = new ResourceController();
        $resource = $resourceController->get($reservation->resource_id);
        if ($resource == null) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "resource not found", null);
        }

        // check overlapping
        $result = $this->getReservedObjectsByTime($resource->id, $reservation->time_from, $reservation->time_to);
        $reservedQuantity = $result[0]->quantity;
        $maxQuantity = $resource->quantity;

        if ($reservedQuantity + $reservation->quantity > $maxQuantity) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "quantity exceeded (" . ($reservedQuantity + $reservation->quantity) . "/" . $maxQuantity . ")", null);
        }

        $rows = $this->updateAll(
            "UPDATE bookings_reservation SET time_from = ",
            array($resource->categoryId, $resource->title, $resource->quantity, $resource->id));

        if ($rows > 0) {
            returnResult($resource);
        } else {
            returnSlimError(GlobalErrors::$NOTHING_CHANGED);
        }
    }
}