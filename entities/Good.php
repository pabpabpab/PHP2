<?php


namespace App\entities;

class Good extends Entity
{
    public $id;
    public $name;
    public $price;
    public $info;
    public $img_folder = 0;
    public $main_img_name = '';
    public $images = [];
    public $number_of_images = 0;

    public function __set($name, $value) {
    }
}