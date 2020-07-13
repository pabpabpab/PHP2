<?php
use App\services\Autoloader;
use App\services\DB;
use App\models\Good;
use App\models\User;

include dirname(__DIR__) . '/services/Autoloader.php';
spl_autoload_register([(new Autoloader()), 'loadClass']);



$good = new Good();
$good->name = 'мега товар';
$good->price = 18;
$good->info = 'супер мега товар';
// $good->id = 43;
$good->save();



$good = new Good();
$good->id = 48;
$good->delete();


$good = new Good();
var_dump($good->getOne(7));
var_dump($good->getAll());

