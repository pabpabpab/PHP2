<?php

namespace App\controllers;

use App\entities\User;
use App\services\PaginatorServices;

class UserController extends Controller
{
    public function allAction()
    {
        $paginator = new PaginatorServices();
        $user = new User();
        $paginator->setItems($user, $this->getPage());
        return $this->render(
            'users',
            [
                'paginator' => $paginator,
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

    public function delAction()
    {
        $id = $this->getId();
        /** @var User $user */
        $user = User::getOne($id);
        $user->delete();
        header("Location: /");
    }
}