<?php

use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/vendor/autoload.php';

$exception = new Ignaszak\Exception\Start;
$exception->errorReporting = E_ALL;
$exception->display = 'dev';
$exception->run();

$router = Start::instance();
$router->baseURI = 'http://192.168.1.2/~tomek/Eclipse/PHP/router/';
$router->add('post', 'post/{alias}.{format:html}/', 'myController');
$router->addToken('alias', '(one)');

$router->run();

echo '<pre>';
print_r(Client::getAllRoutes());
echo '<pre>';
