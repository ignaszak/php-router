<?php

namespace Test\Controller;

use Ignaszak\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Route
     */
    private $route;

    public function setUp()
    {
        $this->route = new Route();
    }

    public function testAdd()
    {
        $this->route->add('name1', 'pattern/subpattern', 'controller1');
        $this->route->add('name2', 'pattern', 'controller2');
        $this->assertEquals(
            [
                0 => [
                    'name' => 'name1',
                    'pattern' => 'pattern/subpattern',
                    'controller' => 'controller1'
                ],
                1 => [
                    'name' => 'name2',
                    'pattern' => 'pattern',
                    'controller' => 'controller2'
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddToken()
    {
        $this->route->addToken('name1', 'token1');
        $this->route->addToken('name2', 'token2');
        $this->assertEquals(
            [
                'name1' => 'token1',
                'name2' => 'token2'
            ],
            $this->route->getTokenArray()
        );
    }

    public function testSort()
    {
        $this->route->add('name1', 'pattern/subpattern', 'controller1');
        $this->route->add('name2', 'pattern', 'controller2');
        $this->route->sort();
        $this->assertEquals(
            [
                0 => [
                    'name' => 'name2',
                    'pattern' => 'pattern',
                    'controller' => 'controller2'
                ],
                1 => [
                    'name' => 'name1',
                    'pattern' => 'pattern/subpattern',
                    'controller' => 'controller1'
                ]
            ],
            $this->route->getRouteArray()
        );
    }
}
