<?php

namespace App\controllers;

use App\models\Good;

class GoodController extends Controller
{
    public $quantityPerPage = 3; // 10

    public function allAction()
    {
        $pageNumber = $this->getId('page');
        if ($pageNumber == 0) $pageNumber = 1;

        return $this->render(
            'goods',
            [
                'goods' => Good::getAllByPage($pageNumber, $this->quantityPerPage),
                'pagesQuantity' => Good::getPagesQuantity($this->quantityPerPage),
            ]
        );
    }

    public function oneAction()
    {
        $id = $this->getId();
        return $this->render(
            'good',
            [
                'good' => Good::getOne($id),
            ]
        );
    }

    public function addAction()
    {
        return $this->render('newGoodForm', []);
    }

    public function editAction()
    {
        $id = $this->getId();
        $_SESSION['editedGoodId'] = $id;
        return $this->render('editGoodForm', [
            'good' => Good::getOne($id),
        ]);
    }

    public function saveAction()
    {
        $id = $this->getId();
        $action = ($id > 0) ? 'update' : 'insert';

        $name = $_POST['name'];
        $price = static::getNumeric($_POST['price']);
        $info = $_POST['info'];

        $error = '';
        if (empty($name)) {
            $error .= 'Не указано наименование товара.<br>';
        }
        if (empty($price)) {
            $error .= 'Не указана цена товара.<br>';
        }
        if (empty($info)) {
            $error .= 'Не указано описание товара.<br>';
        }

        if ($id >0 && $_SESSION['editedGoodId'] != $id) {
            $error .= 'Страница устарела. Повторите операцию.';
        }
        unset($_SESSION['editedGoodId']);

        if (!empty($error)) {
            static::setMSG($error);
            static::redirect();
            return;
        }

        $good = new Good();
        $good->name = $name;
        $good->price = $price;
        $good->info = $info;
        $good->id = ($id > 0) ? $id : 0;

        $msg = '';
        $result = $good->save();

        if ($action == 'update') {
            if (!$result) {
                $msg = 'Товар не обновился.';
            }
        } else {
            $good->id = $result;
            if (empty($good->id)) {
                $msg = 'Товар не добавился.';
            }
        }

        if (!empty($msg)) {
            static::setMSG($msg);
            static::redirect();
            return;
        }

        static::redirect('/?c=good&a=one&id=' . $good->id);
        return;
    }

    public function deleteAction()
    {
        $id = $this->getId();
        if (!Good::delete($id)) {
            static::setMSG('Ошибка удаления.');
            static::redirect();
            return;
        }

        static::setMSG('Товар номер ' . $id . ' удален');
        static::redirect('/?c=good&a=all');
        return;
    }

}