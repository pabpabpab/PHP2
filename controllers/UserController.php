<?php

namespace App\controllers;

use App\entities\User;
use App\services\Paginator;

class UserController extends Controller
{
    public function allAction()
    {
        $entityName = $this->request->getControllerName();

        $paginator = $this->app->paginator;
        $paginator->setItems($entityName, $this->getQuantityPerPage(), $this->getPage());

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
                'user' => $this->app->userRepository()->getOneWithImages($id),
            ]
        );
    }
}