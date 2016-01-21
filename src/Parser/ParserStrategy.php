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

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Controller\Router;

/**
 * Stores reference to RouteController
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Parser/ParserStrategy.php
 *
 */
abstract class ParserStrategy
{

    /**
     * Stores reference to RouteController
     *
     * @var Router
     */
    protected $_routeController;

    /**
     * Stores current query
     *
     * @var array
     */
    protected static $currentQueryArray = array();

    /**
     * Runs parser
     */
    abstract public function run();

    /**
     * Returns $currentQueryArray
     *
     * @return array
     */
    public static function getCurrentQueryArray()
    {
        return self::$currentQueryArray;
    }

    /**
     * Catchs reference to RouteController  class
     *
     * @param \Ignaszak\Router\Controller\Router $_routeController
     */
    public function passReference(\Ignaszak\Router\Controller\Router $_routeController)
    {
        $this->_routeController = $_routeController;
    }
}
