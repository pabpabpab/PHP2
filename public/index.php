<?php

include dirname(__DIR__) . '/services/Autoloader.php';
spl_autoload_register([(new Autoloader()), 'loadClass']);

use App\models\Price;
use App\services\DB;
use App\models\User;
use App\models\Good;
use App\models\Comment as Comments;

$db = new DB();

$user = new User($db);

$price = new Price('евро', 123, 'apiece');
$good = new Good($db);

$comments = new Comments($db);




var_dump($price);
echo '<br><br>';

echo $good->getOne(7) . '<br>';
echo $good->getAll() . '<br><br>';

echo $comments->getAllByGoodId(7) . '<br><br>';

echo $user->getOne(3) . '<br>';

