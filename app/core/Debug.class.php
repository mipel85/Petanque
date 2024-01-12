<?php

namespace App\core;

class Debug
{
    static function dump($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
    
    static function stop($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        exit;
    }
}
?>