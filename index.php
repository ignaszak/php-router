<?php

use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/autoload.php';

$router = Start::instance();

$router->add('post', 'post/{alias}', 'myController');
$router->addToken('alias', '([a-z]*)');
$router->addController('myController', array('file' => __FILE__));
$router->run();

echo '<pre>';
print_r(Client::getAllRoutes());
echo '<pre>';

?>