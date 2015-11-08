<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Ignaszak\Router;

use Ignaszak\Router\Parser\ParserStrategy;

/**
 * Class provides methods for users to get matched routes
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Client.php
 *
 */
class Client implements Interfaces\IClient
{

    /**
     * Returns single matched route
     * 
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
     * Returns route array with defined keys
     * 
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
     * Returns all matched routes in array
     * 
     * @return array
     */
    public static function getAllRoutes()
    {
        return ParserStrategy::getCurrentQueryArray();
    }

    /**
     * Returns route name
     * 
     * @return string
     */
    public static function getRouteName()
    {
        return self::getRoute('name');
    }

    /**
     * Returns true if deined name is the same as current route name
     * 
     * @param string $name
     * @return boolean
     */
    public static function isRouteName($name)
    {
        return (self::getRoute('name') == $name);
    }

    /**
     * Returns default route
     * 
     * @return string
     */
    public static function getDefaultRoute()
    {
        return Conf::get('defaultRoute');
    }

    /**
     * Returns current route controller file
     * 
     * @return string
     */
    public static function getControllerFile()
    {
        $controller = self::getRoute('controller');
        if (!empty($controller))
            return $controller['file'];
    }

}
