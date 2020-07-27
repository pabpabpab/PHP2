<?php


namespace App\traits;


trait Redirect
{
    protected function redirect($path = '') {
        if (!empty($path)) {
            header("location: {$path}");
            return 0;
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("location: {$_SERVER['HTTP_REFERER']}");
            return 0;
        }
        header("location: /");
    }
}