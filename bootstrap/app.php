<?php

/**
 * PHP Settings
 */
declare(strict_types = 1);

/**
 * Constants
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);

/**
 * Autoload classes when they are called on
 */
spl_autoload_register('autoloader');

function autoloader($className)
{
    $path = ROOT . 'Classes' . DS;
    $ext = '.php';

    if (file_exists($path . $className . $ext)) {
        require_once($path . $className . $ext);
    } else {
        throw new Exception("The class $className was not found");
    }
}
