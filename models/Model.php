<?php
namespace App\models;

use App\services\DB;

abstract class Model
{
    /**
     * @var DB
     */
    protected $db;

    /**
     * Возвращает название таблицы
     *
     * @return string
     */
    abstract public function getTableName(): string;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function __set($name, $value){}

    public function getOne($id)
    {
        $this->db->fetchClass = get_class($this);
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = :id';
        return $this->db->find($sql, [':id' => $id]);
    }

    public function getAll()
    {
        $this->db->fetchClass = get_class($this);
        $sql = 'SELECT * FROM ' . $this->getTableName() ;
        return $this->db->findAll($sql);
    }

    public function delete()
    {
        $params[':id'] = $this->id;

        $sql = 'DELETE FROM ' .
        $this->getTableName() .
        ' WHERE id = :id';

        $this->db->execute($sql, $params);
    }

    protected function insert()
    {
        $fields = [];
        $pseudoVars = [];
        $params = [];
        $exept = ['id', 'db'];

        foreach ($this as $key => $value) {
            if (!in_array($key, $exept)) {
                $fields[] = $key;
                $pseudoVars[] = ':' . $key;
                $params[':' . $key] = $this->$key;
            }
        }

        $sql = 'INSERT INTO ' .
        $this->getTableName() .
        ' (' . implode(', ', $fields) . ') ' .
        'VALUES ' .
        ' (' . implode(', ', $pseudoVars) . ') ';

        $this->db->execute($sql, $params);
    }

    protected function update()
    {
        $fields = [];
        $params[':id'] = $this->id;
        $exept = ['id', 'db'];

        foreach ($this as $key => $value) {
            if (!in_array($key, $exept)) {
                $fields[] = $key . ' = :' . $key;
                $params[':' . $key] = $this->$key;
            }
        }

        $sql = 'UPDATE ' .
        $this->getTableName() .
        ' SET ' . implode(', ', $fields) .
        ' WHERE id = :id';

        $this->db->execute($sql, $params);
    }

    public function save()
    {
        if (empty($this->id)) {
            return $this->insert();
        }
        return $this->update();
    }
}