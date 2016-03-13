<?php

use Ignaszak\Router\Route;
use Ignaszak\Router\Router;
use Ignaszak\Router\Client;

include __DIR__ . '/autoload.php';

// Create Router instance to collect routes
$route = Route::start();

// Add new route
// Name is not required but if is defined it must be unique
// for each defined routes.
$route->add('test', 'test/(\w+)/');

// Add token
$route->add(null, 'post/{slug}/')->token('slug', '(\w+)');

// It is possible to define global token avilable for all routes.
// Local tokens overrides global tokens.
$route->addToken('slug', '(\w+)/');

// Add multi tokens in array
$route->addTokens([
    'user' => '(\w+)',
    'page' => '(\d+)'
]);
$route->add(null, 'tokens/')->tokens([]);

// Add controller
$route->add('user', 'user/{user}/')->controller('UserController');

// Define controller from route
$route->add(null, 'test/{controller}/{action}')
    ->controller('\\Namespace\\{controller}::{action}')
    ->tokens([
        'controller' => '([a-zA-Z]+)',
        'action' => '([a-zA-Z]+)'
    ]);

// Add attachment
$route->add('attach', 'attach/{name}/(\w+)/{id}/')
    ->attach(function ($name, $string, $id) {
        echo "{$name}, {$string}, {$id}";
    });
// Disable auto calling
$route->add(null, 'attach/test/')->attach(function () {
    /* Do something */
}, false);

// Group routes
// Every added route after this method will be in the same group
$route->group('groupName');
// Disable group
$route->group();

// Add defined regular expressions
// Router provides some defined regular expressions such as:
//   @base     - use to define default route
//   @notfound - not found
//   @digit    - digits [0-9]
//   @alpha    - alphabetic characters [A-Za-z_-]
//   @alnum    - alphanumeric characters [A-Za-z0-9_-]
$route->add('defined', 'regex/@alpha/{id}')->token('id', '@digit');

// Add default route
$route->add('default', '@base')->controller('DefaultController');

// Not found
$route->add('error', '@notfound')->attach(function () {
    throw new Exception('404 Not found');
});

// Get response
$router = new Router($route);

// Set baseURI - optional (default gets value from $_SERVER['SERVER_NAME'])
// $router->baseURI = 'http://baseuri.com/';

// Define custom regular expression. It will be avilable for all routes
$router->addPattern('day', '([0-9]{2})');
$router->addPatterns([
    'month' => '([0-9]{2})',
    'year' => '([0-9]{4})'
]);
$route->add(null, '@year/@month/@day/');

// Start parsing
$router->run();

// Display response
// Display matched routes
echo 'Routes:<pre>';
print_r(Client::getRoutes());
echo '</pre>';

// Get concrete route
echo Client::getRoute('token');

// Get route name
echo 'Route name: ';
echo Client::getName();
echo '<br />';

// Get route group
echo 'Route group: ';
echo Client::getGroup();
echo '<br />';

// Get route controller
echo 'Controller: ';
echo Client::getController();
echo '<br />';

// Get attachment
//$attachment = Client::getAttachment();
//$attachment();

// Get link
echo 'Link: ';
echo Client::getLink('user', [
    'user' => 'UserName'
]);
