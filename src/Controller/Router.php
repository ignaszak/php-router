<?php

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
     * @var array
     */
    protected static $addedRouteArray = array();

    /**
     * @var array
     */
    protected static $tokenNameArray = array();

    /**
     * @var array
     */
    protected static $tokenPatternArray = array();

    /**
     * @var array
     */
    protected static $controllerArray = array();

    /**
     * @param string $name
     * @param string $pattern
     * @param string $controller
     */
    abstract public function add($name, $pattern, $controller = null);

    /**
     * @param string $name
     * @param string $pattern
     */
    abstract public function addToken($name, $pattern);

    /**
     * @param string $name
     * @param array $options
     */
    abstract public function addController($name, array $options);

    abstract public function run();

    /**
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
     * @param string $name
     * @param string $pattern
     * @param string $controller
     * @param array $key
     * @throws Exception
     */
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
