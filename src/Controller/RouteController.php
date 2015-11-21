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

use Ignaszak\Router\Parser\ParserStrategy;
use Ignaszak\Router\Conf;

/**
 * Adds defined by user routes, tokens and controllers and runs route parser
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Controller/RouteController.php
 *
 */
class RouteController extends Router
{

    /**
     * Stores instance of parser class
     * 
     * @var ParserStrategy
     */
    private $_parser;

    /**
     * Sets parser instance and pass self reference to parser
     * 
     * @param ParserStrategy $_parser
     */
    public function __construct(ParserStrategy $_parser)
    {
        $this->_parser = $_parser;
        $this->_parser->passReference($this);
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::add()
     */
    public function add($name, $pattern, $controller = null)
    {
        $routeArray = parent::createRouteArray($name, $pattern, $controller);
        parent::$addedRouteArray[] = $routeArray;
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::addToken()
     */
    public function addToken($name, $pattern)
    {
        if (!empty($name) && !empty($pattern) && !in_array("{{$name}}", parent::$tokenNameArray)) {
            parent::$tokenNameArray[] = "{{$name}}";
            parent::$tokenPatternArray[] = $pattern;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::addController()
     */
    public function addController($name, array $options)
    {
        parent::$controllerArray[$name] = $options;
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::run()
     */
    public function run()
    {
        $this->add(Conf::get('defaultRoute'), '(.*)');
        $this->sortAddedRouteArray();
        $this->_parser->run();
    }

    /**
     * Sorts route array
     */
    private function sortAddedRouteArray()
    {
        usort(parent::$addedRouteArray,
            function($a, $b)
            {
                return strnatcmp($b['pattern'], $a['pattern']);
            }
        );
    }

}
