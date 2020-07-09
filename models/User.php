<?php
namespace App\models;

class User extends Model
{
    protected $name;
    protected $login;
    protected $password;

    protected $tableName = 'users';

    protected function getTableName(): string
    {
        return 'users';
    }
}