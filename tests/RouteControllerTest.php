<?php

namespace Test;

use Ignaszak\Router\RouteController;

class RouteControllerTest extends \PHPUnit_Framework_TestCase
{

    private $_routeContrroler;
    private $_router;

    public function __construct()
    {
        $this->_routeContrroler = new RouteController;
        $stub = $this->getMockForAbstractClass('Ignaszak\\Router\\Router');
        $this->_router = $stub;
    }

    public function testAdd()
    {
        $this->_routeContrroler->add('name', '{token}', 'controller');
        $addedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'addedRouteArray');

        $output = array(
            'name' => 'name',
            'pattern' => '{token}',
            'controller' => 'controller'
        );

        $this->assertEquals(array($output), $addedRouteArray);
    }

    public function testAddToken()
    {
        $this->_routeContrroler->addToken('token', '([a-z]*)');
        $tokenNameArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'tokenNameArray');
        $tokenPatternArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'tokenPatternArray');

        $this->assertEquals(array('{token}'), $tokenNameArray);
        $this->assertEquals(array('([a-z]*)'), $tokenPatternArray);
    }

    public function testAddController()
    {
        $this->_routeContrroler->addController('name', array('file'=>'file.php'));
        $controllerArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'controllerArray');

        $output = array('name'=>array('file'=>'file.php'));
        $this->assertEquals($output, $controllerArray);
    }

    public function testRun()
    {
        new ConfTest;

        $this->_routeContrroler->run();
        $currentQueryArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'currentQueryArray');

        $output = array(
            'name' => 'name',
            'token' => 'router',
            'controller' => 'controller'
        );
        $this->assertEquals($output, $currentQueryArray);
    }

}
