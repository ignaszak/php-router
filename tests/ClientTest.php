<?php
namespace Test;

use Ignaszak\Router\Client;
use Ignaszak\Router\Interfaces\IRouteParser;
use Test\Mock\MockTest;
use Ignaszak\Router\Link;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        IRouteParser::$request = [
            'name' => 'anyRouteName',
            'controller' => 'AnyController',
            'routes' => [
                'token' => 'anyPattern2'
            ]
        ];
    }

    public function testGetName()
    {
        $this->assertEquals('anyRouteName', Client::getName());
    }

    public function testGetController()
    {
        $this->assertEquals('AnyController', Client::getController());
    }

    public function testGetRoute()
    {
        $this->assertEquals('anyPattern2', Client::getRoute('token'));
    }

    public function testGetNoExistingRoute()
    {
        $this->assertEmpty(Client::getRoute('noExistingRoute'));
    }

    public function testGetRoutes()
    {
        $this->assertEquals(
            [
                'token' => 'anyPattern2'
            ],
            Client::getRoutes()
        );
    }

    public function testGetLink()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Link')
            ->disableOriginalConstructor()->setMethods(['getLink'])->getMock();
        $stub->expects($this->once())->method('getLink');
        MockTest::inject(Link::instance(), 'link', $stub);
        Client::getLink('routeName', []);
    }
}
