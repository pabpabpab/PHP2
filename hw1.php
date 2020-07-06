<?php


class Price
{
    public $name;
    public $total;
    public $discount;

    public function __construct($name, $total, $discount)
    {
        $this->name = $name;
        $this->total = $total;
        $this->discount = $discount;
    }
}


class Good
{
    protected $id;
    protected $name;
    protected $price;
    protected $info;

    public function __construct($id, $name, Price $price, $info)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->info = $info;
    }

    protected function getId()
    {
        return $this->id;
    }
    protected function getNameHtml()
    {
        return "<h1>{$this->name}</h1>";
    }
    protected function getPriceHtml()
    {
        return "<p>Цена: {$this->price->total} {$this->price->name} (скидка {$this->price->discount}%)</p>";
    }
    protected function getInfoHtml()
    {
        return "<p>{$this->info}</p>";
    }
}


class GoodElectric extends Good
{
   protected $voltage;
   protected $power;

    public function __construct($id, $name, $price, $info, $voltage, $power)
    {
        parent::__construct($id, $name, $price, $info);
        $this->voltage = $voltage;
        $this->power = $power;
    }

    protected function getBasicPropertiesHtml()
    {
        return "<p>Напряжение {$this->voltage}В, мощность {$this->power}Вт</p>";
    }

    public function render() {
        echo $this->getNameHtml() . $this->getPriceHtml() . $this->getBasicPropertiesHtml() . $this->getInfoHtml() . '<hr>';
    }
}


class GoodElectricProgrammable extends GoodElectric
{
    protected $properties = [];

    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    protected function getExtraPropertiesHtml()
    {
        $extra = [];
        foreach ($this->properties as $value) {
            $extra[] = $value;
        }
        return "<p>Доп.характеристики: " . implode(', ' , $extra) . "</p>";
    }

    public function render() {
        echo $this->getNameHtml() .
             $this->getPriceHtml() .
             $this->getBasicPropertiesHtml() .
             $this->getExtraPropertiesHtml() .
             $this->getInfoHtml() .
             '<hr>';
    }
}





$price = new Price('евро', 123, 10);
$electricGood = new GoodElectric(5,
    'Электрический нагреватель',
    $price,
    'Информация о товаре элетрический нагреватель',
220,
2000);
$electricGood->render();



$price = new Price('евро', 235, 15);
$programmableElectricGood = new GoodElectricProgrammable(5,
    'Электрический нагреватель программируемый',
    $price,
    'Информация о товаре программируемый элетрический нагреватель',
    220,
    1500);
$programmableElectricGood->display = 'LED дисплей';
$programmableElectricGood->timer = 'таймер на 24 часа';
$programmableElectricGood->console = 'пульт управления';
$programmableElectricGood->temperature = 'регулировка температуры';
$programmableElectricGood->render();






