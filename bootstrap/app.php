<?php

/**
 * Constants
 */
define('DIR', __DIR__);
define('DS', DIRECTORY_SEPARATOR);

/**
 * Autoload classes when they are called on
 */
spl_autoload_register('autoloader');

function autoloader($className)
{
    $path = DIR . DS . '..' . DS . 'Classes' . DS;
    $ext = '.php';

    if (file_exists($path . $className . $ext)) {
        require_once($path . $className . $ext);
    } else {
        throw new Exception("The class $className was not found");
    }
}
