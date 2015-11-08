<?php

namespace Ignaszak\Router\Controller;

use Ignaszak\Router\Parser\ParserStrategy;
use Ignaszak\Router\Conf;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Controller/RouteController.php
 *
 */
class RouteController extends Router
{

    /**
     * @var ParserStrategy
     */
    private $_parser;

    /**
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
        parent::$addedRouteArray = array_merge(parent::$addedRouteArray, array($routeArray));
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::addToken()
     */
    public function addToken($name, $pattern)
    {
        if (!empty($name) && !empty($pattern) && !in_array('{'.$name.'}', parent::$tokenNameArray)) {

            parent::$tokenNameArray = array_merge(parent::$tokenNameArray, array('{'.$name.'}'));
            parent::$tokenPatternArray = array_merge(parent::$tokenPatternArray, array($pattern));

        }
    }

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Controller\Router::addController()
     */
    public function addController($name, array $options)
    {
        parent::$controllerArray = array_merge(parent::$controllerArray, array($name => $options));
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
