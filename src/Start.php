<?php

namespace Ignaszak\Router;

class Start implements Interfaces\IStart
{

    private static $_start;
    private $_conf;
    private $_routeController;

    public function __construct()
    {
        $this->_conf = Conf::instance();
        $this->_routeController = new Controller\RouteController(new Parser\RouteParser);
    }

    public static function instance()
    {
        if (empty(self::$_start))
            self::$_start = new Start;

       return self::$_start;
    }

    public function __set($property, $value)
    {
        $this->_conf->setProperty($property, $value);
    }

    public function __call($function, $args)
    {
        if (method_exists($this->_routeController, $function)) {
            return call_user_func_array(array($this->_routeController, $function), $args);
        } else {
            throw new Exception("Call to undefined method Start::$function()");
        }
    }

}
