<?php

namespace Ignaszak\Router\Controller;

use Ignaszak\Router\Exception;

abstract class Router
{

    protected static $addedRouteArray = array();
    protected static $tokenNameArray = array();
    protected static $tokenPatternArray = array();
    protected static $controllerArray = array();

    abstract public function add($name, $pattern, $controller = null);
    abstract public function addToken($name, $pattern);
    abstract public function addController($name, array $options);
    abstract public function run();

    public function getProperty($property)
    {
        if (property_exists($this, $property)) {
            return self::$$property;
        } else {
            throw new Exception("Property <b>$property</b> not found");
        }
    }

    public function createRouteArray($name, $pattern, $controller = null, array $key = null)
    {

        if (!empty($name) || !empty($pattern)) {

            if (is_null($key)) {

                return array(
                    'name' => $name,
                    'pattern' => $pattern,
                    'controller' => $controller
                );

            } else {

                return array(
                    'name' => $name,
                    'pattern' => $pattern,
                    'key' => $key,
                    'controller' => $controller
                );

            }

        } else {

            throw new Exception('Invalid route');

        }
    }

}
