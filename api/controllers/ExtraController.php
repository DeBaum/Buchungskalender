<?php

namespace Bookings\Controller;


require_once __DIR__ . "/../models/ReservationExtra.php";

use api\models\ReservationExtra;
use Bookings\Models\ExtraWithType;
use Bookings\Models\Reservation;
use Exception;
use extras\BaseExtra;
use extras\ExtraHandler;
use function Bookings\returnResult;
use function Bookings\returnSlimError;

class ExtraController extends BaseController
{
    /**
     * @var ExtraController
     */
    private static $instance;

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

    public function getReservationExtras($reservatonId)
    {
        $reservationExtras = [];
        $rows = $this->fetchAll("SELECT extra_id, extra_data FROM bookings_extra_to_reservation
                                 WHERE reservation_id = :d", [$reservatonId]);
        foreach ($rows as $row) {
            array_push($reservationExtras, new ReservationExtra($row->extra_id, $row->extra_data));
        }
        return $reservationExtras;
    }

    /**
     * Synchronizes the Extras of a specific Reservation.
     *
     * @param Reservation $reservaton
     * @param Reservation|null $oldReservation
     * @return bool If all updates were successfull.
     */
    public function syncReservationExtras(Reservation $reservaton, Reservation $oldReservation = null)
    {
        $extras = $reservaton->extras;
        $existingExtras = $oldReservation !== null ? $oldReservation->extras : [];
        /** @var ReservationExtra[] $extrasToUpdate */
        $extrasToUpdate = [];
        /** @var ReservationExtra[] $extrasToInsert */
        $extrasToInsert = [];
        /** @var ReservationExtra[] $extrasToRemove */
        $extrasToRemove = [];

        // check for changes
        foreach ($extras as $extra) {
            foreach ($existingExtras as $existing) {
                if ($existing->extra_id === $extra->extra_id) {
                    // update Extra
                    array_push($extrasToUpdate, $extra);
                    break 2; // next Extra (outer loop)
                }
            }
            // new Extra
            array_push($extrasToInsert, $extra);
        }

        foreach ($existingExtras as $existing) {
            foreach ($extras as $extra) {
                if ($existing->extra_id === $extra->extra_id) {
                    break 2; // next Extra (outer loop)
                }
            }
            array_push($extrasToRemove, $existing);
        }

        // run querys
        if (sizeof($extrasToRemove) > 0) {
            $extraIdsToRemove = join(",", array_map(function ($e) {
                return $e->extra_id;
            }, $extrasToRemove));
            if ($this->updateAll("DELETE FROM bookings_extra_to_reservation
                          WHERE reservation_id = :d AND extra_id IN ($extraIdsToRemove)",
                    [$reservaton->id]) === false
            ) {
                return false;
            }
        }

        foreach ($extrasToInsert as $newExtra) {
            if ($this->updateAll("INSERT INTO bookings_extra_to_reservation (reservation_id, extra_id, extra_data) VALUES (:d, :d, :s)",
                    [$reservaton->id, $newExtra->extra_id, $newExtra->value]) === false
            ) {
                return false;
            };
        }

        foreach ($extrasToUpdate as $extra) {
            if ($this->updateAll("UPDATE bookings_extra_to_reservation SET extra_data = :s
                              WHERE reservation_id = :d AND extra_id = :d",
                    [$extra->value, $reservaton->id, $extra->extra_id]) === false
            ) {
                return false;
            };
        }
        return true;
    }

    private function resolveExtra(ExtraWithType $extra)
    {
        $extra = $this->extraHandler->getExtra($extra->id, $extra->title, $extra->type_id, $extra->type->field_class, $extra->config);
        return $extra;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ExtraController();
        }
        return self::$instance;
    }
}