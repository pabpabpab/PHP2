<?php

namespace App\models;

class Good extends Model
{
    public $id;
    public $name;
    public $price;
    public $info;

    public function getTableName(): string
    {
        return 'products';
    }
}
