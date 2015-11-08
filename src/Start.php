<?php

namespace Ignaszak\Router;

use Ignaszak\Router\Controller\RouteController;
/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Start.php
 *
 */
class Start implements Interfaces\IStart
{

    /**
     * @var Start
     */
    private static $_start;

    /**
     * @var Conf
     */
    private $_conf;

    /**
     * @var RouteController
     */
    private $_routeController;

    public function __construct()
    {
        $this->_conf = Conf::instance();
        $this->_routeController = new Controller\RouteController(new Parser\RouteParser);
    }

    /**
     * @return Conf
     */
    public static function instance()
    {
        if (empty(self::$_start))
            self::$_start = new Start;

       return self::$_start;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value)
    {
        $this->_conf->setProperty($property, $value);
    }

    /**
     * @param string $function
     * @param array $args
     * @throws Exception
     * @return callable
     */
    public function __call($function, $args)
    {
        if (method_exists($this->_routeController, $function)) {
            return call_user_func_array(array($this->_routeController, $function), $args);
        } else {
            throw new Exception("Call to undefined method Start::$function()");
        }
    }

}
