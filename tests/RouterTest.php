<?php

namespace Test;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    private $_router;
    private $output;

    public function __construct()
    {
        $stub = $this->getMockForAbstractClass('Ignaszak\\Router\\Router');
        $this->_router = $stub;

        $this->output = array(
            'name' => 'name',
            'pattern' => 'pattern',
            'key' => array('key'),
            'controller' => 'controller'
        );
    }

    public function testCreateRouteArray()
    {
        $args = array('name', 'pattern', 'controller', array('key'));

        $method = Mock\MockTest::callProtectedMethod($this->_router, 'createRouteArray', $args);

        $this->assertEquals($this->output, $method);
    }

    /**
     * @expectedException \Ignaszak\Router\Exception
     */
    public function testException()
    {
        $args = array('', '');

        Mock\MockTest::callProtectedMethod($this->_router, 'createRouteArray', $args);
    }

    public function testAddMatchedRoute()
    {
        $args = array('name', 'pattern', 'controller', array('key'));

        Mock\MockTest::callProtectedMethod($this->_router, 'addMatchedRoute', $args);
        $matchedRouteArray = \PHPUnit_Framework_Assert::readAttribute($this->_router, 'matchedRouteArray');

        $this->assertEquals(array($this->output), $matchedRouteArray);
    }

}
