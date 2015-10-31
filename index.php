<?php

use Ignaszak\Router\Start;

include __DIR__ . '/autoload.php';

$router = Start::instance();
$router->baseURL = 'http:://example.com';

$router->add('post', '(post)/{alias}');
$router->run();

?>