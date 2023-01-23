<?php

spl_autoload_register(function ($className) {
    if (substr($className, 0, 3) == 'App') {
        $className = str_replace('\\', '/', $className);
        $className = str_replace('App/', '', $className);
        $className = ("src/$className.php");
        require_once($className);
    }
});
