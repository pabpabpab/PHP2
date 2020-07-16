<?php


namespace App\controllers;


abstract class Controller
{
    protected $action;
    protected $actionDefault = 'all';

    public function run($action)
    {
        session_start();

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
        $content = $this->rendererTmpl($template, $params);
        return $this->rendererTmpl(
            'layouts/main',
            [
                'content' => $content,
                'msg' => static::getMSG(),
            ]
        );
    }

    public function rendererTmpl($template, $params = [])
    {
        ob_start();
        extract($params);
        include dirname(__DIR__) . '/views/' . $template . '.php';
        return ob_get_clean();
    }



    protected static function getId($key = 'id')
    {
        if (!empty((int)$_GET[$key])) {
            return (int) $_GET[$key];
        }
        return 0;
    }

    protected static function setMSG($msg) {
        $_SESSION['msg'] = $msg;
    }

    protected static function getMSG() {
        $msg = '';
        if (!empty($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        return $msg;
    }

    protected static function redirect($path = '') {
        if (!empty($path)) {
            header("location: {$path}");
            return;
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("location: {$_SERVER['HTTP_REFERER']}");
            return;
        }
        header("location: /");
    }

    protected static function getNumeric($input) {
        if (is_numeric($input)) {
            return $input + 0;
        }
        return 0;
    }

}