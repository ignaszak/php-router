<?php

namespace Ignaszak\Router;

class Client extends Router implements Interfaces\IClient
{

    public static function getRoute($route = '')
    {
        if (is_string($route)) {

            if (empty($route)) {

                return (empty(parent::$currentQueryArray['route1']) ?
                    Conf::get('defaultRoute') : parent::$currentQueryArray['route1']);

            } else {

                return (array_key_exists($route, parent::$currentQueryArray) ? 
                    parent::$currentQueryArray[$route] : null);

            }

        }
    }

    public static function getRouteArray(array $route)
    {
        if (is_array($route) && !empty($route)) {

            $match = array();

            foreach ($route as $key) {
                $match[$key] = (isset(parent::$currentQueryArray[$key]) ?
                    parent::$currentQueryArray[$key] : null);
            }

            return $match;

        }
    }

    public static function getAllRoutes()
    {
        return parent::$currentQueryArray;
    }

    public static function getRouteName()
    {
        return self::getRoute('name');
    }

    public static function isRouteName($name)
    {
        return (self::getRoute('name') == $name);
    }

    public static function getDefaultRoute()
    {
        return Conf::get('defaultRoute');
    }

    public static function getControllerFile()
    {
        $name = self::getRoute('controller');
        if (!empty(parent::$controllerArray[$name]['file']))
            return parent::$controllerArray[$name]['file'];
    }

}
