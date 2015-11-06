<?php

namespace Ignaszak\Router\Parser;

abstract class ParserStrategy
{

    protected $_routeController;
    protected static $currentQueryArray = array();

    abstract public function run();

    public static function getCurrentQueryArray()
    {
        return self::$currentQueryArray;
    }

    public function passReference(\Ignaszak\Router\Controller\Router $_routeController)
    {
        $this->_routeController = $_routeController;
    }

}
