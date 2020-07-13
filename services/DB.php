<?php
namespace App\services;

use App\traits\TSingleton;

class DB
{
    use TSingleton;

    protected $connect;
    public $fetchClass;

    protected $config = [
        'driver' =>  'mysql',
        'host' =>  'localhost',
        'dbname' =>  'gbshop',
        'charset' =>  'UTF8',
        'user' => 'root',
        'password' => 'root'
    ];

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

    /**
     * @param $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    protected function query($sql, $params = [])
    {
        $PDOStatement = $this->getConnect()->prepare($sql);
        $PDOStatement->execute($params);
        return $PDOStatement;
    }

    public function find($sql, $params = [])
    {
        if (!empty($this->fetchClass)) {
            return $this->query($sql, $params)->fetchObject($this->fetchClass);
        }

        return $this->query($sql, $params)->fetch();
    }

    public function findAll($sql, $params = [])
    {
        if (!empty($this->fetchClass)) {
            $sth = $this->query($sql, $params);
            $sth->setFetchMode(\PDO::FETCH_CLASS, $this->fetchClass);
            return $sth->fetchAll();
        }

        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * @param $sql
     * @param array $params
     * @return bool|\PDOStatement
     */
    public function execute($sql, $params = [])
    {
        return $this->query($sql, $params);
    }
}
