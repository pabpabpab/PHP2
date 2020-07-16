<?php
use App\services\Autoloader;

include dirname(__DIR__) . '/services/Autoloader.php';
spl_autoload_register([(new Autoloader()), 'loadClass']);

$controller = 'user';
if ($_GET['c']) {
    $controller = $_GET['c'];
}

$action = '';
if (!empty($_GET['a'])) {
    $action = $_GET['a'];
}

$controllerName = 'App\\controllers\\' . ucfirst($controller) . 'Controller';

if (class_exists($controllerName)) {
    /** @var \App\controllers\UserController $realController */
    $realController = new $controllerName();
    $content = $realController->run($action);
    if (!empty($content)) {
        echo $content;
    }
}



