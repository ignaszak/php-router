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
        $this->route = Route::start();
    }

    public function testAddWithName()
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
                    'pattern' => 'pattern/subpattern',
                    'group' => ''
                ],
                'name2' => [
                    'pattern' => 'pattern',
                    'group' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddWithoutNAme()
    {
        $this->route->add(null, 'pattern/subpattern');
        $this->assertEquals(
            0,
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );
        $this->route->add(null, 'pattern');
        $this->assertEquals(
            1,
            \PHPUnit_Framework_Assert::readAttribute($this->route, 'lastName')
        );

        $this->assertEquals(
            [
                0 => [
                    'pattern' => 'pattern/subpattern',
                    'group' => ''
                ],
                1 => [
                    'pattern' => 'pattern',
                    'group' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddDuplicateName()
    {
        $this->route->add('name', 'anyPattern');
        $this->route->add('name', 'anyPattern');
    }

    public function testAddController()
    {
        $this->route->add('anyName', 'anyPattern')->controller('anyController');
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'controller' => 'anyController',
                    'group' => ''
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
                    ],
                    'group' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAddTokensToRoute()
    {
        $this->route->add('anyName', 'anyPattern')->tokens([
            'tokenName1' => 'pattern1',
            'tokenName2' => 'pattern2',
            'tokenName3' => 'pattern3'
        ]);
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'token' => [
                        'tokenName1' => 'pattern1',
                        'tokenName2' => 'pattern2',
                        'tokenName3' => 'pattern3'
                    ],
                    'group' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testAttach()
    {
        $anyAttachment = function () {
        };
        $this->route->add('anyName', 'anyPattern')->attach($anyAttachment);
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'callAttachment' => true,
                    'attachment' => $anyAttachment,
                    'group' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testCallableAttach()
    {
        $anyAttachment = function () {
        };
        $this->route->add('anyName', 'anyPattern')
            ->attach($anyAttachment, false);
        $this->assertEquals(
            [
                'anyName' => [
                    'pattern' => 'anyPattern',
                    'callAttachment' => false,
                    'attachment' => $anyAttachment,
                    'group' => ''
                ],
            ],
            $this->route->getRouteArray()
        );
    }

    public function testGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, 'anyPattern');
        $this->route->add(null, 'anyPattern');
        $this->assertEquals(
            [
                [
                    'pattern' => 'anyPattern',
                    'group' => 'anyGroupName'
                ],
                [
                    'pattern' => 'anyPattern',
                    'group' => 'anyGroupName'
                ]
            ],
            $this->route->getRouteArray()
        );
    }

    public function testClearGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, 'anyPattern');
        $this->route->group();
        $this->route->add(null, 'anyPattern');
        $this->assertEquals(
            [
                [
                    'pattern' => 'anyPattern',
                    'group' => 'anyGroupName'
                ],
                [
                    'pattern' => 'anyPattern',
                    'group' => ''
                ]
            ],
            $this->route->getRouteArray()
        );
    }
}
