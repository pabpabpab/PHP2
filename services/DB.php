<?php
namespace App\services;

use App\traits\TSingleton;

class DB
{
    protected $container;

    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function getConnect()
    {
        if (empty($this->connect)) {
            $this->connect = new \PDO(
                $this->getPrepareDsnString(),
                $this->config['user'],
                $this->config['password']
            );
            $this->connect->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC
            );
        }
        return $this->connect;
    }


    private function getPrepareDsnString()
    {
        return sprintf(
            "%s:host=%s;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['dbname'],
            $this->config['charset']
        );
    }

    protected function query($sql, $params = [])
    {
        $PDOStatement = $this->getConnect()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    public function find($sql, $params = [])
    {
        return $this->query($sql, $params)->fetch();
    }

    public function findAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }

    public function findObject($sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetch();
    }

    public function findObjects($sql, $class, $params = [])
    {
        $PDOStatement = $this->query($sql, $params);
        $PDOStatement->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $PDOStatement->fetchAll();
    }

    public function execute($sql, $params = [])
    {
        return $this->query($sql, $params); // @return \PDOStatement
    }

    public function getInsertId()
    {
        return $this->getConnect()->lastInsertId();
    }

}
