<?php


namespace Bookings\Models;


use help\BookingsHelper;

class BaseModel
{
    protected function isInt($value, $min = 1)
    {
        return BookingsHelper::isInt($value, $min);
    }

    protected function isString($value, $minLength = 1)
    {
        return BookingsHelper::isString($value, $minLength);
    }

    protected function isDate($value)
    {
        return BookingsHelper::isDate($value);
    }
}