<?php

namespace Test;

use Ignaszak\Router\Controller\RouteController;
use Ignaszak\Router\Parser\RouteParser;
use Ignaszak\Router\Parser\ParserStrategy;

class RouteControllerTest extends \PHPUnit_Framework_TestCase
{

    private $_routeContrroler;
    private $_router;
    private $_routeParser;

    public function __construct()
    {
        $this->_routeContrroler = new RouteController(new RouteParser);
        $stub = $this->getMockForAbstractClass('Ignaszak\\Router\\Controller\\Router');
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

        $this->assertEquals($output, $addedRouteArray[0]);
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
        $this->_routeContrroler->addController('controller', array('file'=>'file.php'));
        $controllerArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'controllerArray');

        $output = array('controller'=>array('file'=>'file.php'));
        $this->assertEquals($output, $controllerArray);
    }

    public function testRun()
    {
        new ConfTest;

        $this->_routeContrroler->run();
        $currentQueryArray = ParserStrategy::getCurrentQueryArray();

        $output = array(
            'name' => 'name',
            'token' => 'router',
            'controller' => array('file'=>'file.php')
        );
        $this->assertEquals($output, $currentQueryArray);
    }

    /**
     * @expectedException \Ignaszak\Router\Exception
     */
    public function testCheckForDuplicates()
    {
        $this->_routeContrroler->add('duplicate', '{token}/{token}');
        Mock\MockTest::callProtectedMethod($this->_routeContrroler, 'checkForDuplicates');
    }

}
