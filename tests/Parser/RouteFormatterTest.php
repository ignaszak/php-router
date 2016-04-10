<?php
namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
use Ignaszak\Router\Parser\RouteFormatter;

class RouteFormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Parser
     */
    private $routeFormatter;

    public function setUp()
    {
        $this->routeFormatter = new RouteFormatter($this->mockRoute());
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Collection\IRoute',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->routeFormatter,
                'route'
            )
        );
    }

    public function testGetChecksum()
    {
        $this->assertEmpty($this->routeFormatter->getChecksum());
    }

    public function testPreparePattern()
    {
        $pattern = '/noNamed1/(?P<token>(noNamed2))\.html';
        $result = MockTest::callMockMethod(
            $this->routeFormatter,
            'preparePattern',
            [$pattern]
        );
        $this->assertEquals(
            '/^\/noNamed1\/(?P<token>(noNamed2))\.html$/',
            $result
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testValidRouteWithBrokenRoute()
    {
        $route = '/route/@pattern/{token}/';
        MockTest::callMockMethod(
            $this->routeFormatter,
            'validPattern',
            [$route]
        );
    }

    public function testValidRouteWithRightRoute()
    {
        $route = '/route/route2/';
        $this->assertTrue(
            MockTest::callMockMethod(
                $this->routeFormatter,
                'validPattern',
                [$route]
            )
        );
    }

    public function testFormat()
    {
        $routeArray = [
            'routes' => [
                'name' => [
                    'path' => '/test/{token1}/@digit/{globaltoken}',
                    'tokens' => [
                        'token1' => '(\w+)'
                    ]
                ]
            ],
            'tokens' => [
                'token1' => '([a-z]+)',
                'globaltoken' => '@alnum'
            ]
        ];
        $this->routeFormatter = new RouteFormatter(
            $this->mockRoute($routeArray)
        );
        MockTest::callMockMethod(
            $this->routeFormatter,
            'format'
        );
        $this->assertEquals(
            [
                'name' => [
                    'path' => '/^\/test\/(?P<token1>(\w+))\/(\d+)\/(?P<globaltoken>([\w-]+))$/',
                    'tokens' => [
                        'token1' => '(\w+)',
                        'globaltoken' => '([\w-]+)'
                    ],
                    'route' => '/test/{token1}/@digit/{globaltoken}'
                ]
            ],
            \PHPUnit_Framework_Assert::readAttribute(
                $this->routeFormatter,
                'routeArray'
            )
        );
    }

    public function testSort()
    {
        MockTest::inject($this->routeFormatter, 'routeArray', [
            'name2' => [
                'path' => '/pattern',
                'group' => ''
            ],
            'name1' => [
                'path' => '/pattern/subpattern',
                'group' => ''
            ]
        ]);
        MockTest::callMockMethod(
            $this->routeFormatter,
            'sort'
        );
        $this->assertEquals(
            [
                'name1' => [
                    'path' => '/pattern/subpattern',
                    'group' => ''
                ],
                'name2' => [
                    'path' => '/pattern',
                    'group' => ''
                ]
            ],
            $this->routeFormatter->getRouteArray()
        );
    }

    public function testGetPatterns()
    {
        $this->routeFormatter = new RouteFormatter(
            $this->mockRoute([
                'patterns' => [
                    'pattern' => 'regex'
                ]
            ])
        );
        $this->assertTrue(
            array_key_exists(
                '@pattern',
                MockTest::callMockMethod(
                    $this->routeFormatter,
                    'getPatterns'
                )
            )
        );
    }

    private function mockRoute(
        array $route = []
    ) {
        $stub = $this->getMockBuilder('Ignaszak\Router\Collection\IRoute')
            ->disableOriginalConstructor()->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }
}
