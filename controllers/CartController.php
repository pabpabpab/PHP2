<?php


namespace App\controllers;


use App\services\Cart;


class CartController extends Controller
{
    public function allAction()
    {
        return $this->render(
            'cart',
            [
                'cart' => $this->app->cart
            ]
        );
    }

    public function addAction()
    {
        $id = $this->getId();
        $result = $this->app->cart->add($id);
        if (!$result) {
            $this->setMSG('Товар не добавился в корзину.');
        }
        $this->redirect('/cart');
        return 0;
    }

    public function deleteAction()
    {
        $id = $this->getId();
        $result = $this->app->cart->delete($id);
        if (!$result) {
            $this->setMSG('Ошибка удаления товара из корзины.');
        }
        $this->redirect('/cart');
        return 0;
    }

    public function saveAction()
    {
        $result = $this->app->cart->save($this->post('goodsCount'));
        if (!$result) {
            $this->setMSG('Изменения не сохранены.');
        }
        $this->redirect('/cart');
        return 0;
    }
}

