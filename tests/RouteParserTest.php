<?php

namespace Test;

use Ignaszak\Router\Parser\RouteParser;
use Ignaszak\Router\Controller\RouteController;
use Ignaszak\Router\Parser\ParserStrategy;

class RouteParserTest extends \PHPUnit_Framework_TestCase
{

    private $_routeParser;
    private $_routeContrroler;

    public function setUp()
    {
        new ConfTest;

        $this->_routeParser = new RouteParser;
        $this->_routeContrroler = new RouteController($this->_routeParser);

        $this->_routeContrroler->add('name', '{token}', 'controller');
        $this->_routeContrroler->addToken('token', '([a-z]*)');
        $this->_routeContrroler->addController('controller', array('file'=>'file.php'));
    }

    public function testMatchRouteWithToken()
    {
        Mock\MockTest::callProtectedMethod($this->_routeParser, 'matchRouteWithToken');
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_routeParser, 'matchedRouteArray');
        $count = count($matchedRouteArray);

        $output = array(
            'name' => 'name',
            'pattern' => '([a-z]*)',
            'key' => array('token'),
            'controller' => array('file'=>'file.php')
        );

        $this->assertEquals($output, $matchedRouteArray[$count - 1]);
    }

    public function testMatchPatternWithQueryString()
    {
        Mock\MockTest::callProtectedMethod($this->_routeParser, 'matchRouteWithToken');
        Mock\MockTest::callProtectedMethod($this->_routeParser, 'matchPatternWithQueryString');
        $currentQueryArray = ParserStrategy::getCurrentQueryArray();

        $output = array(
            'name' => 'name',
            'controller' => array('file'=>'file.php'),
            'token' => 'router'
        );

        $this->assertEquals($output, $currentQueryArray);
    }

    public function testAddMatchedRoute()
    {
        $args = array('name', 'pattern', 'controller', array('key'));

        Mock\MockTest::callProtectedMethod($this->_routeParser, 'addMatchedRoute', $args);
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_routeParser, 'matchedRouteArray');

        $output = array(
            'name' => 'name',
            'pattern' => 'pattern',
            'key' => array('key'),
            'controller' => 'controller'
        );

        $this->assertEquals(array($output), $matchedRouteArray);
    }

}
