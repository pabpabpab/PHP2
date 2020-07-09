<?php


namespace App\services;


interface IDB
{
    public function find($sql);
    public function findAll($sql);
}