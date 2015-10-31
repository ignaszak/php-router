<?php

namespace Ignaszak\Router\Interfaces;

interface IRouteController
{

    public function add($name, $pattern, $controller = null);
    public function addToken($name, $pattern);
    public function addController($name, array $options);
    public function run();

}
