<?php
namespace App\models;
// use App\services\TTest;

class Good extends Model
{
    // use TTest;

    protected $id;
    protected $name;
    protected $price;
    protected $info;

    protected function getTableName(): string
    {
        return 'goods';
    }
}
