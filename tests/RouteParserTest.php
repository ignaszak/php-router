<?php

namespace Test;

use Ignaszak\Router\Parser\RouteParser;
use Ignaszak\Router\Controller\RouteController;
use Ignaszak\Router\Parser\ParserStrategy;

class RouteParserTest extends \PHPUnit_Framework_TestCase
{

    private $_routeParser;
    private $_routeController;

    public function setUp()
    {
        new ConfTest;

        $this->_routeParser = new RouteParser;
        $this->_routeController = new RouteController($this->_routeParser);

        $this->_routeController->add('name', '{token}', 'controller');
        $this->_routeController->addToken('token', '([a-z]*)');
    }

    public function testMatchRouteWithToken()
    {
        Mock\MockTest::callProtectedMethod($this->_routeParser, 'matchRouteWithToken');
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_routeParser, 'matchedRouteArray');
        $count = count($matchedRouteArray);

        $output = array(
            'name' => 'name',
            'pattern' => '(?P<token>([a-z]*))',
            'controller' => 'controller'
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
            'controller' => 'controller',
            'token' => 'router'
        );

        $this->assertEquals($output, $currentQueryArray);
    }

    public function testAddMatchedRoute()
    {
        $args = array('name', 'pattern', 'controller');

        Mock\MockTest::callProtectedMethod($this->_routeParser, 'addMatchedRoute', $args);
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_routeParser, 'matchedRouteArray');

        $output = array(
            'name' => 'name',
            'pattern' => 'pattern',
            'controller' => 'controller'
        );

        $this->assertEquals(array($output), $matchedRouteArray);

    }

    public function testAddNameToPatternWithDefinedToken()
    {
        $addNameToPatternWithDefinedToken = Mock\MockTest::callProtectedMethod($this->_routeParser, 'addNameToPatternWithDefinedToken', array('{token}'));
        $this->assertEquals("(?P<token>([a-z]*))", $addNameToPatternWithDefinedToken);
    }

    public function testAddNameToPattern()
    {
        $addNameToPattern = Mock\MockTest::callProtectedMethod($this->_routeParser, 'addNameToPattern', array('{token:router}'));
        $this->assertEquals("(?P<token>router)", $addNameToPattern);

        $addNameToPattern = Mock\MockTest::callProtectedMethod($this->_routeParser, 'addNameToPattern', array('{router}'));
        $this->assertEquals("(?P<route1>router)", $addNameToPattern);
    }
}
