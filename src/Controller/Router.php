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

namespace Ignaszak\Router\Controller;

use Ignaszak\Router\Exception;

/**
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Controller/Router.php
 *
 */
abstract class Router
{

    /**
     * Stores added routes
     *
     * @var array
     */
    protected static $addedRouteArray = array();

    /**
     * Stores added tokens name
     *
     * @var array
     */
    protected static $tokenNameArray = array();

    /**
     * Stores added tokens pattern
     *
     * @var array
     */
    protected static $tokenPatternArray = array();

    /**
     * Adds to $addedRouteArray route name, pattern and if defined controller name
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     */
    abstract public function add($name, $pattern, $controller = null);

    /**
     * Adds to $tokenNameArray and $tokenPatternArray token name and pattern
     *
     * @param string $name
     * @param string $pattern
     */
    abstract public function addToken($name, $pattern);

    /**
     * Adds default route, witch is activated when no routes is matched.
     * Sorts $addedRouteArray and runs route parser.
     */
    abstract public function run();

    /**
     * Returns properties
     *
     * @param string $property
     * @throws Exception
     */
    public function getProperty($property)
    {
        if (property_exists($this, $property)) {
            return self::$$property;
        } else {
            throw new Exception("Property <b>$property</b> not found");
        }
    }

    /**
     * Returns route array
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     * @param array $key
     * @throws Exception
     */
    public function createRouteArray($name, $pattern, $controller = null)
    {
        if (!empty($name) || !empty($pattern)) {

            return array(
                'name' => $name,
                'pattern' => $pattern,
                'controller' => $controller
            );

        } else {

            throw new Exception('Invalid route');

        }
    }
}
