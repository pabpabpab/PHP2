<?php


namespace App\traits;


trait MsgMaker
{
    protected function setMSG($msg) {
        $_SESSION['msg'] .= $msg;
    }

    protected function getMSG() {
        $msg = '';
        if (!empty($_SESSION['msg'])) {
            $msg = $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        return $msg;
    }
}