<?php

namespace help;


class BookingsHelper
{
    public static function isDate($value)
    {
        return strtotime($value) !== false;
    }
}