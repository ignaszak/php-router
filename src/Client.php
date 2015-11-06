<?php

namespace Ignaszak\Router;

use Ignaszak\Router\Parser\ParserStrategy;

class Client implements Interfaces\IClient
{

    public static function getRoute($route = '')
    {
        if (is_string($route)) {

            $currentQueryArray = ParserStrategy::getCurrentQueryArray();

            if (empty($route)) {

                return (empty($currentQueryArray['route1']) ?
                    Conf::get('defaultRoute') : $currentQueryArray['route1']);

            } else {

                return (array_key_exists($route, $currentQueryArray) ? 
                    $currentQueryArray[$route] : null);

            }

        }
    }

    public static function getRouteArray(array $route)
    {
        if (is_array($route) && !empty($route)) {

            $currentQueryArray = ParserStrategy::getCurrentQueryArray();
            $match = array();

            foreach ($route as $key) {
                $match[$key] = (isset($currentQueryArray[$key]) ?
                    $currentQueryArray[$key] : null);
            }

            return $match;

        }
    }

    public static function getAllRoutes()
    {
        return ParserStrategy::getCurrentQueryArray();
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
        $controller = self::getRoute('controller');
        if (!empty($controller))
            return $controller['file'];
    }

}
