<?php

namespace App\services;


class Request
{
    private $requestString = '';
    private $controllerName = 'good';
    private $actionName = '';
    private $id = 0;
    private $page = 1;
    private $quantityPerPage = 3; // 10
    private $params = [
        'get' => array(),
        'post' => array(),
        'session' => array(),
        'files' => array(),
    ];

    public function __construct()
    {
        session_start();
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->prepareRequest();
    }


    protected function prepareRequest()
    {
        $pattern = "#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?[?]?(?P<params>.*)#ui";

        if (preg_match_all($pattern, $this->requestString, $matches)) {
            $this->controllerName = $matches['controller'][0];
            $this->actionName = $matches['action'][0];
        }

        $this->params = [
            'get' => $_GET,
            'post' => $_POST,
            'session' => $_SESSION,
            'files' => $_FILES,
        ];

        if (!empty((int)$_GET['id'])) {
            $this->id = (int)$_GET['id'];
        }

        if (!empty((int)$_GET['page'])) {
            $this->page = (int)$_GET['page'];
        }
    }


    public function getControllerName(): string
    {
        return  $this->controllerName;
    }

    public function getFullControllerName(): string
    {
        return  'App\\controllers\\' . ucfirst($this->controllerName) . 'Controller';
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getQuantityPerPage(): int
    {
        return $this->quantityPerPage;
    }

    public function post($key = '')
    {
        if (empty($key)) {
           return $this->params['post'];
        }
        return $this->params['post'][$key];
    }

    public function session($key = '', $defaultValue = null)
    {
        if (empty($key)) {
            return $this->params['session'];
        }

        if (!empty($this->params['session'][$key])) {
            return $this->params['session'][$key];
        }

        return $defaultValue;
    }

    public function setSession($key, $value)
    {
       $_SESSION[$key] = $value;
       $this->params['session'] = $_SESSION;
    }

    public function files($key = '', $defaultValue = null)
    {
        if (empty($key)) {
            return $this->params['files'];
        }

        if (!empty($this->params['files'][$key])) {
            return $this->params['files'][$key];
        }

        return $defaultValue;
    }

    // не используется, оставлю для примера
    public function getResponse($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }

}

