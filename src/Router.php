<?php

namespace Ignaszak\Router;

abstract class Router
{

    protected static $currentQueryArray = array();
    protected static $addedRouteArray = array();
    protected static $tokenNameArray = array();
    protected static $tokenPatternArray = array();
    protected static $matchedRouteArray = array();
    protected static $controllerArray = array();

    protected static function addMatchedRoute($name, $pattern, $controller = null, array $key = null)
    {
        $routeArray = self::createRouteArray($name, $pattern, $controller, $key);
        self::$matchedRouteArray = array_merge(self::$matchedRouteArray, array($routeArray));
    }

    protected static function createRouteArray($name, $pattern, $controller = null, array $key = null)
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
