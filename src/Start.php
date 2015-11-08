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

use Ignaszak\Router\Controller\RouteController;
/**
 * Initializes router
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Start.php
 *
 */
class Start implements Interfaces\IStart
{

    /**
     * Stores instance of Start class
     * 
     * @var Start
     */
    private static $_start;

    /**
     * Stores instance of Conf class
     * 
     * @var Conf
     */
    private $_conf;

    /**
     * Stores instance of RouteController class
     * 
     * @var RouteController
     */
    private $_routeController;

    /**
     * Sets instances of Conf and RouteController classes
     */
    public function __construct()
    {
        $this->_conf = Conf::instance();
        $this->_routeController = new Controller\RouteController(new Parser\RouteParser);
    }

    /**
     * Singelton design pattern
     * 
     * @return Conf
     */
    public static function instance()
    {
        if (empty(self::$_start))
            self::$_start = new Start;

       return self::$_start;
    }

    /**
     * Sets Conf property value
     * 
     * @param string $property
     * @param string $value
     */
    public function __set($property, $value)
    {
        $this->_conf->setProperty($property, $value);
    }

    /**
     * Calls RouteController methods
     * 
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
