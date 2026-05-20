<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../helpers.php';
require __DIR__ . '/../vendor/autoload.php';

use Framework\Router;
use Framework\Database;

// Instantiate the router
$router = new Router();

// Get routes
$routes = require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];  // ← ADD THIS

// Route the request
$router->route($uri, $method);  // ← ADD $method here