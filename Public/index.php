<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../helpers.php';          // ← ADD THIS FIRST
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Database;

// Instantiate the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);

//$routes = [
//    '/' => 'controllers/home.php',
//    'listings' => 'controllers/listings/index.php',
//    '/listings/create' => 'controllers/listings/create.php',
//    '404' => 'controllers/error/404.php'
//];

//$uri = $_SERVER['REQUEST_URI'];
//    if(array_key_exists($uri, $routes)) {
//        require(basePath($routes[$uri]));
//    } else {
//        require basePath ($routes['404']);
//    }
?>