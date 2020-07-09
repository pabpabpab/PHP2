<?php


namespace App\models;


abstract class Discount
{
    public static $types = [
        'apiece' => 10,
        'digital' => 30,
        'weight' => 20
    ];

    public static function getDiscount($key)
    {
       return self::$types[$key];
    }
}