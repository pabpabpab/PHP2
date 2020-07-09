<?php


class Autoloader
{
    public function loadClass($className) {
        $tmp = str_replace(['App\\', '\\'], ['', '/'], $className);
        $file = dirname(__DIR__) . '/' . $tmp . '.php';
        if (is_file($file)) {
            include_once $file;
            return;
        }
        exit('Не удалось подключить класс ' . $className);
    }
}