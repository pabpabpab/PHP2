<?php
namespace App\models;

class User extends Model
{
    protected $name;
    protected $login;
    protected $password;

    protected function getTableName(): string
    {
        return 'users';
    }
}
