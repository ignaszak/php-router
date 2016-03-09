<?php

use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/vendor/autoload.php';

// Error reporting configuration for development
$exception = new Ignaszak\Exception\Start;
$exception->errorReporting = E_ALL;
$exception->display = 'dev';
$exception->run();

// Set router instnce
$router = Start::instance();

// Set baseURI - optional (default gets value from $_SERVER['SERVER_NAME'])
// $router->baseURI = 'www.example.com';

// Adds route by calling Start::add(string $name, string $pattern)
// Name must be unique for each defined routes
// It is possible to use regular expression
$router->add('name1', 'pattern/anotherPattern/[a-z]*/');

// Adds token for route (:tokenName)
$router->add('name2', 'route/:alias.:format')
    ->controller('AnyController') // define controller class name
    ->token('format', '(html|xml)'); // token avilable only local
$router->addToken('alias', '\w+'); // token avilable for all routes
// It is posible to override global token

// Adds defined regular expressions
// Router provides some defined regular expressions such as:
//   @default  - use to define default route
//   @digit    - digits [0-9]
//   @alpha    - Alphabetic characters [A-Za-z_-]
//   @alnum    - alphanumeric characters [A-Za-z0-9_-]
$router->add('name3', 'route/:page/:post/')->token('page', '@digit');
$router->addToken('post', '@alnum');

// Adds default route
// Defult route is active when no routes is match
$router->add('default', '@default')->controller('DefaultController');

// Initialize router
$router->run();

// Get request
// Display matched route
echo '<pre>';
print_r(Client::getRoutes());
echo '</pre>';

// Get concrete route
echo Client::getRoute('post');
