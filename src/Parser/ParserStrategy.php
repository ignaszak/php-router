<?php

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Controller\Router;
/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Parser/ParserStrategy.php
 *
 */
abstract class ParserStrategy
{

    /**
     * @var Router
     */
    protected $_routeController;

    /**
     * 
     * @var array
     */
    protected static $currentQueryArray = array();

    abstract public function run();

    /**
     * @return array
     */
    public static function getCurrentQueryArray()
    {
        return self::$currentQueryArray;
    }

    /**
     * @param \Ignaszak\Router\Controller\Router $_routeController
     */
    public function passReference(\Ignaszak\Router\Controller\Router $_routeController)
    {
        $this->_routeController = $_routeController;
    }

}
