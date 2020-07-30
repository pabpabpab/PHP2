<?php


namespace App\services;

use \App\engine\App;


class Cart extends Service
{
    public $goods = [];
    public $count = 0;
    public $totalPrice = 0;

    public function __construct()
    {
       if (is_array($this->session('goods'))) {
           $this->goods = $this->session('goods');
       }

       $this->count = $this->getCount();
       $this->totalPrice = $this->getTotalPrice();
    }

    public function add($id)
    {
        if (!($this->isGoodExists($id))) {
            return false;
        }

        $good = $this->container->goodRepository->getOne($id);

        if (!is_array($this->goods[$id])) {
            $this->goods[$id]['name'] = $good->name;
            $this->goods[$id]['price'] = $good->price;
            $this->goods[$id]['count'] = 1;
            $this->goods[$id]['totalPrice'] = $good->price;
        } else {
            $this->goods[$id]['count'] += 1;
            $this->goods[$id]['totalPrice'] = $this->goods[$id]['price'] * $this->goods[$id]['count'];
        }

        $this->refresh();
        return true;
    }

    public function delete($id)
    {
        if (!is_array($this->goods[$id])) {
            return false;
        }

        if ($this->goods[$id]['count'] > 1) {
            $this->goods[$id]['count'] -= 1;
            $this->goods[$id]['totalPrice'] = $this->goods[$id]['price'] * $this->goods[$id]['count'];
        } else {
            unset($this->goods[$id]);
        }

        $this->refresh();
        return true;
    }

    public function save($goodsCount)
    {
        foreach ($goodsCount as $id=>$count) {
            if (!($this->isGoodExists($id))) {
                return false;
            }
            $this->goods[$id]['count'] = $count;
            $this->goods[$id]['totalPrice'] = $this->goods[$id]['price'] * $this->goods[$id]['count'];
        }

        $this->refresh();
        return true;
    }

    protected function refresh()
    {
        $this->count = $this->getCount();
        $this->totalPrice = $this->getTotalPrice();
        $this->setSession();
    }

    protected function getTotalPrice()
    {
        $total = 0;
        foreach ($this->goods as $good) {
            $total += $good['count'] * $good['price'];
        }
        $total = number_format($total, 2, '.', '');
        return $total;
    }

    protected function getCount()
    {
        $count = 0;
        foreach ($this->goods as $good) {
            $count += $good['count'];
        }
        return $count;
    }

    protected function isGoodExists($id)
    {
        return $this->container->goodRepository->isExists($id);
    }

    protected function setSession()
    {
        $this->container->request->setSession('goods', $this->goods);
        $this->container->request->setSession('cartCount', $this->getCount());
    }

    protected function session($key)
    {
        /*
         * данный метод вызывается из конструктора,         *
         * который в свою очередь вызывается в Container при создании объекта типа Cart,
         * при этом метод setContainer для Cart еще не вызван,
         * и свойство container не установлено,
         * поэтому здесь применяю такой способ получения объекта request.
        */
        $request = App::call()->request;
        return $request->session($key);
    }
}