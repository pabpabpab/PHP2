<?php


namespace App\models;


class Comment extends Model
{
    protected $id;
    protected $goodId;
    protected $text;

    protected function getTableName(): string
    {
        return 'comments';
    }
}