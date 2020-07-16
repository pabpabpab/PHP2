<?php
namespace App\models;

use App\services\DB;

abstract class Model
{
    /**
     * Возвращает название таблицы
     *
     * @return string
     */
    abstract public static function getTableName(): string;

    protected static function getDB()
    {
        return DB::getInstance();
    }

    public function __set($name, $value){}

    public static function getOne($id)
    {
        $sql = 'SELECT * FROM ' . static::getTableName() . ' WHERE id = :id';
        return static::getDB()->findObject($sql, static::class, [':id' => $id]);
    }

    public static function getAll()
    {
        $sql = 'SELECT * FROM ' . static::getTableName();
        return static::getDB()->findObjects($sql, static::class);
    }

    public static function getPagesQuantity($quantityPerPage)
    {
        $sql = 'SELECT count(*) as quantity FROM ' . static::getTableName();
        $result = static::getDB()->find($sql);
        return ceil($result['quantity']/$quantityPerPage);
    }

    public static function getAllByPage($pageNumber, $quantityPerPage)
    {
        $from = $pageNumber * $quantityPerPage - $quantityPerPage;
        $sql = 'SELECT * FROM ' . static::getTableName() . ' LIMIT ' . $from . ' ,' . $quantityPerPage;
        return static::getDB()->findObjects($sql, static::class);
    }



    public static function delete($id)
    {
        $params[':id'] = $id;

        $sql = 'DELETE FROM ' .
        static::getTableName() .
        ' WHERE id = :id';

        return static::getDB()->execute($sql, $params);
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
                $params[':' . $key] = $value;
            }
        }

        $sql = 'INSERT INTO ' .
        $this->getTableName() .
        ' (' . implode(', ', $fields) . ') ' .
        'VALUES ' .
        ' (' . implode(', ', $pseudoVars) . ') ';

        static::getDB()->execute($sql, $params);
        return static::getDB()->getInsertId(); // last id
    }

    protected function update()
    {
        $fields = [];
        $params[':id'] = $this->id;
        $exept = ['id', 'db'];

        foreach ($this as $key => $value) {
            if (!in_array($key, $exept)) {
                $fields[] = $key . ' = :' . $key;
                $params[':' . $key] = $value;
            }
        }

        $sql = 'UPDATE ' .
        $this->getTableName() .
        ' SET ' . implode(', ', $fields) .
        ' WHERE id = :id';

        return static::getDB()->execute($sql, $params);
    }

    public function save()
    {
        if (empty($this->id)) {
            return $this->insert();
        }
        return $this->update();
    }

}