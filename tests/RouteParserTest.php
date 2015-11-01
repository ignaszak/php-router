<?php

namespace Test;

use Ignaszak\Router\RouteParser;
use Ignaszak\Router\RouteController;

class RouteControllerTest extends \PHPUnit_Framework_TestCase
{

    private $_routeParser;
    private $_routeContrroler;
    private $_router;

    public function setUp()
    {
        new ConfTest;

        $this->_routeParser = new RouteParser;
        $this->_routeContrroler = new RouteController;
        $stub = $this->getMockForAbstractClass('Ignaszak\\Router\\Router');
        $this->_router = $stub;

        $this->_routeContrroler->add('name', '{token}', 'controller');
        $this->_routeContrroler->addToken('token', '([a-z]*)');
        $this->_routeContrroler->addController('name', array('file'=>'file.php'));
    }

    public function testMatchRouteWithToken()
    {
        $this->_routeParser->matchRouteWithToken();
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'matchedRouteArray');

        $output = array(
            'name' => 'name',
            'pattern' => '([a-z]*)',
            'key' => array('token'),
            'controller' => 'controller'
        );

        $this->assertEquals(array($output), $matchedRouteArray);
    }

    public function testMatchPatternWithQueryString()
    {
        $this->_routeParser->matchPatternWithQueryString();
        $currentQueryArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'currentQueryArray');

        $output = array(
            'name' => 'name',
            'controller' => 'controller',
            'token' => 'router'
        );
        $this->assertEquals($output, $currentQueryArray);
    }

}
