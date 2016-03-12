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
// $router->baseURI = 'http://example.com';

// Adds route by calling Start::add(string $name, string $pattern)
// Name must be unique for each defined routes
// It is possible to use regular expression
$router->add('name1', 'pattern/anotherPattern/[a-z]*/');

// Adds token for route {tokenName}
$router->add('name2', 'route/{alias}.{format}')
    ->controller('AnyController') // define controller class name
    ->token('format', '(html|xml)'); // token avilable only local
$router->addToken('alias', '\w+'); // token avilable for all routes
// It is posible to override global token

// Adds defined regular expressions
// Router provides some defined regular expressions such as:
//   @base  - use to define default route
//   @404   - not found
//   @digit - digits [0-9]
//   @alpha - alphabetic characters [A-Za-z_-]
//   @alnum - alphanumeric characters [A-Za-z0-9_-]
$router->add('name3', 'route/{page}/{post}/')->token('page', '@digit');
$router->addToken('post', '@alnum');

// Define custom regular expression. It will be avilable for all routes
$router->addPattern('custom', '([a-z]{2,5})');
$router->add('routeWithCustomRegEx', 'route/@custom/');

// Adds default route
$router->add('default', '@base')->controller('DefaultController');

// Not found
$router->add('error', '@404')->controller('ErrorController');

// Adds attachment
$router->add('attachment', 'attach/{name}/([a-z]+)/{post}/@digit/')
    ->token('name', '@alpha')
    ->attach(function ($name, $string, $post, $digit) {
        echo "{$name}, {$string}, {$post}, {$digit}";
    });

// Initialize router
$router->run();

// Get request
// Display matched routes
echo 'Routes:<pre>';
print_r(Client::getRoutes());
echo '</pre>';

// Get concrete route
echo Client::getRoute('tokenName');

// Get route name
echo 'Route name: ';
echo Client::getName();
echo '<br />';

// Get route controller
echo 'Controller: ';
echo Client::getController();
echo '<br />';

// Get attachment
$attachment = Client::getAttachment();
$attachment();

// Get link
echo 'Link: ';
echo Client::getLink('attachment', [
    'name' => 'Tomek'
]);
