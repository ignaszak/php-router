<?php

use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/autoload.php';

$router = Start::instance();
$router->baseURL = 'http://192.168.1.2/~tomek/Eclipse/PHP/Router/';

$router->add('post', 'post/{alias}', 'myController');
$router->addToken('alias', '(one|two)');
$router->addController('myController', array('file' => __FILE__));

$router->add('post', 'post/{alias:three}', 'myController2');
$router->addController('myController2', array('file' => 2));

$router->run();

echo '<pre>';
print_r(Client::getAllRoutes());
echo '<pre>';

?>