<?php

namespace help;


class BookingsHelper
{
    public static function isInt($value, $min = 1)
    {
        if (!is_numeric($value)) {
            return false;
        }

        return $value >= $min;
    }

    public static function isString($value, $minLength = 1)
    {
        if ($value == null) {
            return false;
        }
        $value = trim($value);

        return strlen($value) >= $minLength;
    }

    public static function isDate($value)
    {
        return strtotime($value) !== false;
    }
}