<?php

use App\App;
use Core\Acl\ACL;
use Core\Configuration\Config;
use Core\Controllers\ErrorController;
use Core\Router\Router;
use Core\Router\RouterException;
use Core\Utils\Request;

define('ROOT', __DIR__ .  '/..');

// Start session
session_start();

// Require autoload
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/App.php';
require __DIR__ . '/../Core/Exception/Exception.php';

// Init app
App::init();

// Maintenance
$maintenanceMode = App::getConfig(Config::MAINTENANCE_MODE_CONFIG_CODE) == 1;
if ($maintenanceMode) {
    return new ErrorController('maintenance');
}

// Set router URL
$request = new Request();
$router = new Router($request->getUrl());

/**
 * Index route
 * @GET METHOD
 */
$router->get('/');

/**
 * Unique route
 * @GET METHOD
 */
$router->get('/:id', ACL::EVERYONE);

/**
 * Unique blog route
 * @GET METHOD
 */
$router->get('/login', ACL::NOT_LOGGED_IN);
$router->get('/register', ACL::NOT_LOGGED_IN);

/**
 * Admin route
 * @GET METHOD
 */
$router->get('/admin', ACL::ADMIN);

// Run router
try {
    $router->run();
} catch (RouterException $e) {
    echo $e->getMessage();
}