<?php


namespace App\controllers;


use App\services\Cart;


class CartController extends Controller
{
    public function allAction()
    {
        $cart = new Cart($this->request);
        return $this->render(
            'cart',
            [
                'cart' => $cart
            ]
        );
    }

    public function addAction()
    {
        $id = $this->getId();
        $result = (new Cart($this->request))->add($id);
        if (!$result) {
            $this->setMSG('Товар не добавился в корзину.');
        }
        $this->redirect('/cart');
        return 0;
    }

    public function deleteAction()
    {
        $id = $this->getId();
        $result = (new Cart($this->request))->delete($id);
        if (!$result) {
            $this->setMSG('Ошибка удаления товара из корзины.');
        }
        $this->redirect('/cart');
        return 0;
    }

    public function saveAction()
    {
        $result = (new Cart($this->request))->save($this->post('goodsCount'));
        if (!$result) {
            $this->setMSG('Изменения не сохранены.');
        }
        $this->redirect('/cart');
        return 0;
    }
}

