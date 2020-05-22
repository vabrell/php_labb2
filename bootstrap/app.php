<?php

/**
 * PHP Settings
 */

declare(strict_types=1);

/**
 * Constants
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS . '..' . DS);


/**
 * Composer Autoloader
 */
require_once(PATH . 'vendor/autoload.php');

/**
 * Start the session
 */
session_start();