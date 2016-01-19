<?php


namespace Bookings\Models;


use help\BookingsHelper;

class BaseModel
{

    protected function isInt($value, $min = 1)
    {
        if (!is_numeric($value)) {
            return false;
        }

        return $value >= $min;
    }

    protected function isString($value, $minLength = 1)
    {
        if ($value == null) {
            return false;
        }
        $value = trim($value);

        return strlen($value) >= $minLength;
    }

    protected function isDate($value)
    {
        return BookingsHelper::isDate($value);
    }
}