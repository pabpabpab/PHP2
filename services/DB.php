<?php
namespace App\services;

class DB implements IDB
{
    public function find($sql) {
        return $sql;
    }
    public function findAll($sql) {
        return $sql;
    }
}