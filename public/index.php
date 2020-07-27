<?php
// use App\services\Autoloader;
// use App\services\NewException;
use App\services\TwigRendererServices;
use App\services\Request;


require_once dirname(__DIR__) . '/vendor/autoload.php';

$request = new Request();

$controllerName = $request->getFullControllerName();

if (class_exists($controllerName)) {
    $realController = new $controllerName(
        new TwigRendererServices(),
        $request
    );
    $content = $realController->run($request->getActionName());
    if (!empty($content)) {
        echo $content;
    }
} else {
    echo "Не найден класс " . $controllerName;
}



