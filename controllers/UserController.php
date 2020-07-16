<?php

namespace App\controllers;

use App\models\User;

class UserController extends Controller
{
    public function allAction()
    {
        return $this->render(
            'users',
            [
                'users' => User::getAll(),
            ]
        );
    }

    public function oneAction()
    {
        $id = $this->getId();
        return $this->render(
            'user',
            [
                'user' => User::getOne($id),
            ]
        );
    }
}