<?php

namespace App\models;

class Good extends Model
{
    public $id;
    public $name;
    public $price;
    public $info;

    public static function getTableName(): string
    {
        return 'products';
    }
}
