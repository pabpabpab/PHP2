<?php

use App\services\TwigRendererServices;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$controller = 'user';
if ($_GET['c']) {
    $controller = $_GET['c'];
}

$action = '';
if (!empty($_GET['a'])) {
    $action = $_GET['a'];
}



$controllerName = 'App\\controllers\\' . ucfirst($controller) . 'Controller';


$pathToTemplates = dirname(__DIR__) . '/views/';
$loader = new \Twig\Loader\FilesystemLoader($pathToTemplates);
$twig = new \Twig\Environment($loader, [
    'autoescape' => false,
]);


if (class_exists($controllerName)) {
    /** @var \App\controllers\UserController $realController */
    $realController = new $controllerName(new TwigRendererServices($twig));
    $content = $realController->run($action);
    if (!empty($content)) {
        echo $content;
    }
}


