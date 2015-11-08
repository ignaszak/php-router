<?php

namespace Ignaszak\Router;

use Ignaszak\Router\Parser\ParserStrategy;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Client.php
 *
 */
class Client implements Interfaces\IClient
{

    /**
     * @param string $route
     * @return string|null
     */
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

    /**
     * @param array $route
     * @return array|null
     */
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

    /**
     * @return array
     */
    public static function getAllRoutes()
    {
        return ParserStrategy::getCurrentQueryArray();
    }

    /**
     * @return string
     */
    public static function getRouteName()
    {
        return self::getRoute('name');
    }

    /**
     * @param string $name
     * @return boolean
     */
    public static function isRouteName($name)
    {
        return (self::getRoute('name') == $name);
    }

    /**
     * @return string
     */
    public static function getDefaultRoute()
    {
        return Conf::get('defaultRoute');
    }

    /**
     * @return string
     */
    public static function getControllerFile()
    {
        $controller = self::getRoute('controller');
        if (!empty($controller))
            return $controller['file'];
    }

}
