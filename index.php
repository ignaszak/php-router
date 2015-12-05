<?php

use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/autoload.php';

$router = Start::instance();

$router->add('post', 'post/{alias}', 'myController');
$router->addToken('alias', '(one|two)');
$router->add('post', 'post/{alias:three}', 'myController2');

$router->run();

echo '<pre>';
print_r(Client::getAllRoutes());
echo '<pre>';

?>