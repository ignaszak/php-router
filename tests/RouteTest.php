<?php

namespace Test;

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
        $this->route->add('name1', 'pattern/subpattern');
        $this->assertEquals(
            'name1',
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );
        $this->route->add('name2', 'pattern');
        $this->assertEquals(
            'name2',
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );

        $this->assertEquals(
            [
                'name1' => [
                    'pattern' => 'pattern/subpattern'
                ],
                'name2' => [
                    'pattern' => 'pattern'
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddController()
    {
        $this->route->add('anyName', 'anyPattern')->controller('anyController');
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'controller' => 'anyController'
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddTokenToRoute()
    {
        $this->route->add('anyName', 'anyPattern')
            ->token('anyTokenName', 'anyPattern');
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'token' => [
                        'anyTokenName' => 'anyPattern'
                    ]
                ],
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
        $this->route->add('name1', 'pattern/subpattern');
        $this->route->add('name2', 'pattern');
        $this->route->sort();
        $this->assertEquals(
            [
                'name2' => [
                    'pattern' => 'pattern'
                ],
                'name1' => [
                    'pattern' => 'pattern/subpattern'
                ]
            ],
            $this->route->getRouteArray()
        );
    }
}
