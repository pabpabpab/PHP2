<?php

namespace App\controllers;


use App\services\IRenderer;
use App\services\Request;
use App\traits\MsgMaker;
use App\traits\Redirect;

abstract class Controller
{
    use MsgMaker;
    use Redirect;

    protected $action;
    protected $actionDefault = 'all';

    protected $renderer;
    protected $request;

    public function __construct(IRenderer $renderer, Request $request)
    {
        $this->renderer = $renderer;
        $this->request = $request;
    }

    public function run($action)
    {
        $this->action = $action;
        if (empty($this->action)) {
            $this->action = $this->actionDefault;
        }

        $method = $this->action . "Action";
        if (!method_exists($this, $method)) {
            return 'Error';
        }

        return $this->$method();
    }

    public function render($template, $params = [])
    {
        return $this->renderer->render($template, $params);
    }

    protected function getId()
    {
        return $this->request->getId();
    }

    protected function getPage()
    {
        return $this->request->getPage();
    }

    protected function getQuantityPerPage()
    {
        return $this->request->getQuantityPerPage();
    }

    protected function post($key)
    {
        return $this->request->post($key);
    }

    protected function session($key)
    {
        return $this->request->session($key);
    }

    protected function files($key)
    {
        return $this->request->files($key);
    }
}
