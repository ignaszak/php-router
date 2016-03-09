<?php
namespace Test;

use Ignaszak\Router\Client;
use Ignaszak\Router\Interfaces\IRouteParser;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        IRouteParser::$request = [
            'name' => 'anyRouteName',
            'controllr' => 'anyController',
            'route1' => 'anyPattern1',
            'token' => 'anyPattern2'
        ];
    }

    public function testGetRoute()
    {
        $this->assertEquals('anyRouteName', Client::getRoute('name'));
        $this->assertEquals('anyController', Client::getRoute('controllr'));
        $this->assertEquals('anyPattern1', Client::getRoute('route1'));
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
                'name' => 'anyRouteName',
                'controllr' => 'anyController',
                'route1' => 'anyPattern1',
                'token' => 'anyPattern2'
            ],
            Client::getRoutes()
        );
    }
}
