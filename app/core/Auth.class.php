<?php

namespace App\core;

class Auth
{
    static function is_connected() : bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['connected']);
    }

    static function force_user_connected() : void
    {
        if(!self::is_connected())
        {
            header('Location: ./login.php');
            exit();
        }
    }

    static function is_local()
    {
        if (in_array($_SERVER['HTTP_HOST'], array('localhost', 'local.pbt')))
            return true;
        return false;
    }
}