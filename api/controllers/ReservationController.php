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

        $this->validateExtras($reservation);

        $this->startTransaction();
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
            $reservation->id = $inserted_id;
            if (ExtraController::getInstance()->syncReservationExtras($reservation, null) === true) {
                $this->commit();
                returnResult($this->get($inserted_id));
                return true;
            }
        }

        $this->rollback();
        returnSlimError(GlobalErrors::$INTERNAL_ERROR);
        return false;
    }

    /**
     * Calculates the quantity of reserved (unavailable) objects
     * within a specific date range
     *
     * @param $resourceId
     * @param $from
     * @param $to
     *
     * @param null $ignoredReservationId If set reservations with this Id will be ignored
     * @return array|null|object
     */
    public function getReservedObjectsByTime($resourceId, $from, $to, $ignoredReservationId = null)
    {
        $query = "SELECT SUM(quantity) AS quantity FROM bookings_reservation WHERE resource_id = :d AND :s < time_to AND :s > time_from";
        $args = array(
            $resourceId,
            $from,
            $to
        );
        if ($ignoredReservationId !== null) {
            $query .= " AND reservation_id != :d";
            array_push($args, $ignoredReservationId);
        }
        return $this->fetchAll($query, $args);
    }

    public function get($id)
    {
        $extraController = ExtraController::getInstance();
        $reservation = Reservation::fromDb($this->fetchOne("SELECT * FROM bookings_reservation WHERE id = :d", $id));
        if ($reservation === null) {
            return returnSlimError(GlobalErrors::$RESOURCE_NOT_FOUND);
        }
        $reservation->extras = $extraController->getReservationExtras($reservation->id);
        return $reservation;
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

        $extraController = ExtraController::getInstance();
        $reservations = array();
        foreach ($this->fetchAll("SELECT * FROM bookings_reservation WHERE resource_id = :d AND :s <= time_to AND :s >= time_from",
            [$resourceId, $from, $to]) as $row) {
            $reservation = Reservation::fromDb($row);
            $reservation->extras = $extraController->getReservationExtras($reservation->id);
            array_push($reservations, $reservation);
        }

        return $reservations;
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
        $result = $this->getReservedObjectsByTime($resource->id, $reservation->time_from, $reservation->time_to, $reservation->id);
        $reservedQuantity = $result[0]->quantity;
        $maxQuantity = $resource->quantity;

        if ($reservedQuantity + $reservation->quantity > $maxQuantity) {
            return returnSlimError(GlobalErrors::$INVALID_REQUEST, "quantity exceeded (" . ($reservedQuantity + $reservation->quantity) . "/" . $maxQuantity . ")", null);
        }

        $this->validateExtras($reservation);

        $this->startTransaction();
        $rows = $this->updateAll(
            "UPDATE bookings_reservation SET time_from = :s, time_to = :s, event_from = :s, event_to = :s, quantity = :d
             WHERE id = :d",
            array(
                $reservation->time_from,
                $reservation->time_to,
                $reservation->event_from,
                $reservation->event_to,
                $reservation->quantity,
                $reservation->id
            )
        );

        if ($rows !== false) {
            if (ExtraController::getInstance()->syncReservationExtras($reservation, $existingReservation) === true) {
                $this->commit();
                returnResult($reservation);
                return true;
            }
        }

        $this->rollback();
        returnSlimError(GlobalErrors::$INTERNAL_ERROR);
        return false;
    }

    /**
     * Validates a list of Extras.
     *
     * @param Reservation $reservation
     * @return bool If the list of Extras is valid.
     */
    private function validateExtras(Reservation $reservation)
    {
        // empty/null values stand for "not set" so remove them
        $reservation->extras = array_filter($reservation->extras, function ($extra) {
            $val = $extra->value;
            return $val !== null && (!is_numeric($val) || intval($val) > 0);
        });
        // TODO Validation
        return true;
    }
}