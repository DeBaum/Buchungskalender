<?php

namespace api\models;


include_once __DIR__ . "/BaseModel.php";

use Bookings\Models\BaseModel;

class ReservationExtra extends BaseModel
{
    public $extra_id;
    public $value;

    /**
     * ReservationExtra constructor.
     * @param int $extraId Extra identifier
     * @param string $value value
     */
    public function __construct($extraId, $value)
    {
        if (!$this->isInt($extraId, 1)) {
            throw new \InvalidArgumentException("ReservationExtra is missing extra_id");
        }
        $this->extra_id = $extraId;
        $this->value = $value;
    }
}