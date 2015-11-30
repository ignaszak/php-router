<?php

namespace Test;

use Ignaszak\Router\Controller\RouteController;
use Ignaszak\Router\Parser\RouteParser;
use Ignaszak\Router\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    private $_routeContrroler;
    private $output;

    public function __construct()
    {
        new ConfTest;

        $this->_routeContrroler = new RouteController(new RouteParser);

        $this->_routeContrroler->add('name', '{token}', 'controller');
        $this->_routeContrroler->addToken('token', '([a-z]*)');
        $this->_routeContrroler->run();

        $this->output = array(
            'name' => 'name',
            'controller' => 'controller',
            'token' => 'router'
        );
    }

    public function testGetRoute()
    {
        $this->assertEquals('', Client::getRoute());
        $this->assertEquals($this->output['token'], Client::getRoute('token'));
    }

    public function testGetAllRoutes()
    {
        $this->assertEquals($this->output, Client::getAllRoutes());
    }

    public function testGetRouteName()
    {
        $this->assertEquals($this->output['name'], Client::getRouteName());
    }

    public function testIsRouteName()
    {
        $this->assertEquals(false, Client::isRouteName('otherName'));
    }

    public function testGetDefaultRoute()
    {
        $this->assertEquals('', Client::getDefaultRoute());
    }

}
