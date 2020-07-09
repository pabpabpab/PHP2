<?php

namespace App\models;
use App\services\DB;

abstract class Model
{
    protected $db;
    protected $tableName;

    abstract protected function getTableName(): string;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function getOne($id)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE id = " . $id;
        return $this->db->find($sql);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->getTableName();
        return $this->db->findAll($sql);
    }

    public function getAllByGoodId($goodId)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " WHERE good_id = " . $goodId;
        return $this->db->findAll($sql);
    }
}