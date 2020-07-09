<?php


namespace App\models;


class Price
{
    protected $name;
    protected $total;
    protected $discountType;
    protected $discount;

    public function __construct($name, $total, $discountType)
    {
        $this->name = $name;
        $this->total = $total;
        $this->discountType = $discountType;
        $this->discount = Discount::getDiscount($discountType);
    }
}