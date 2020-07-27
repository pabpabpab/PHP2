<?php

namespace App\services;

interface IRenderer
{
    public function render($template, $params = []);
}