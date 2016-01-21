<?php

namespace Test;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    private $_router;
    private $output;

    public function __construct()
    {
        $stub = $this->getMockForAbstractClass('Ignaszak\\Router\\Controller\\Router');
        $this->_router = $stub;

        $this->output = array(
            'name' => 'name',
            'pattern' => 'pattern',
            'controller' => 'controller'
        );
    }

    public function testCreateRouteArray()
    {
        $args = array('name', 'pattern', 'controller');

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
}
