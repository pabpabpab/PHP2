<?php

namespace App\repositories;

use App\entities\Entity;
use App\services\DB;

abstract class Repository
{
    abstract public function getTableName(): string;
    abstract public function getEntityName(): string;

    /**
     * @return DB
     */
    protected static function getDB()
    {
        return DB::getInstance();
    }


    public function getRowsCount()
    {
        $sql = "SELECT count(*) AS `count` FROM " . $this->getTableName();
        $result = static::getDB()->find($sql);
        return $result['count'];
    }

    public function getPagesQuantity($quantityPerPage)
    {
        $rowsCount = $this->getRowsCount();
        return ceil($rowsCount/$quantityPerPage);
    }

    public function getAllByPage($page = 1, $quantityPerPage = 10)
    {
        $start = ($page - 1) * $quantityPerPage;
        $sql = "SELECT * FROM " . $this->getTableName() . " LIMIT " . $start . ", " . $quantityPerPage;
        return static::getDB()->findObjects($sql, $this->getEntityName());
    }

    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() ;
        return static::getDB()->findObjects($sql, $this->getEntityName());
    }

    public function getOne($id)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id = :id';
        return static::getDB()->findObject($sql, $this->getEntityName(), [':id' => $id]);
    }

    public function isExists($id)
    {
        $sql = "SELECT count(*) AS `count` FROM " . $this->getTableName() . ' WHERE id = :id';
        $result = static::getDB()->find($sql, [':id' => $id]);
        if ($result['count'] != 1) {
            return false;
        }
        return true;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE id = :id';
        $pdoStatement = static::getDB()->execute($sql, [':id' => $id]);
        return $pdoStatement->rowCount(); // number of affected rows
    }

    public function insert(Entity $entity)
    {
        $columns = [];
        $params = [];
        $except = ['id'];

        foreach ($entity as $key => $value) {
            if (!in_array($key, $except) && !empty($value)) {
                $columns[] = $key;
                $params[':' . $key] = $value;
            }
        }

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->getTableName(),
            implode(',', $columns),
            implode(',', array_keys($params))
        );

        static::getDB()->execute($sql, $params);
        return static::getDB()->getInsertId(); // last id
    }

    protected function update(Entity $entity)
    {
        $columns = [];
        $params[':id'] = $entity->id;
        $except = ['id'];

        foreach ($entity as $key => $value) {
            if (!in_array($key, $except)) {
                $columns[] = $key . ' = :' . $key;
                $params[':' . $key] = $value;
            }
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE id = :id",
            $this->getTableName(),
            implode(', ', $columns)
        );

        $pdoStatement = static::getDB()->execute($sql, $params);
        return $pdoStatement->rowCount(); // number of affected rows
    }


    public function save(Entity $entity)
    {
        if (empty($entity->id)) {
            return $this->insert($entity);
        }
        return $this->update($entity);
    }
}
