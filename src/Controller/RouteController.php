<?php

namespace Ignaszak\Router\Controller;

use Ignaszak\Router\Parser\ParserStrategy;
use Ignaszak\Router\Conf;

class RouteController extends Router
{

    private $_parser;

    public function __construct(ParserStrategy $_parser)
    {
        $this->_parser = $_parser;
        $this->_parser->passReference($this);
    }

    public function add($name, $pattern, $controller = null)
    {
        $routeArray = parent::createRouteArray($name, $pattern, $controller);
        parent::$addedRouteArray = array_merge(parent::$addedRouteArray, array($routeArray));
    }

    public function addToken($name, $pattern)
    {
        if (!empty($name) && !empty($pattern) && !in_array('{'.$name.'}', parent::$tokenNameArray)) {

            parent::$tokenNameArray = array_merge(parent::$tokenNameArray, array('{'.$name.'}'));
            parent::$tokenPatternArray = array_merge(parent::$tokenPatternArray, array($pattern));

        }
    }

    public function addController($name, array $options)
    {
        parent::$controllerArray = array_merge(parent::$controllerArray, array($name => $options));
    }

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
