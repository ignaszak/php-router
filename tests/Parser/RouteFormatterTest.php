<?php

namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
use Ignaszak\Router\Route;
use Ignaszak\Router\Conf\Conf;
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
            'Ignaszak\Router\Route',
            $this->routeFormatter->getRoute()
        );
    }

    public function testAddRouteTokens()
    {
        $route = [
            'pattern' => '{token1}(anyPattern)/{token2}.{format}',
            'token' => [
                'token1' => 'pattern1',
                'token2' => 'pattern2',
                'format' => 'pattern3'
            ]
        ];
        $result = MockTest::callMockMethod(
            $this->routeFormatter,
            'addTokens',
            [$route['token'], $route['pattern']]
        );
        $this->assertEquals(
            '(?P<token1>pattern1)(anyPattern)/(?P<token2>pattern2).(?P<format>pattern3)',
            $result
        );
    }

    public function testAddEmptyTokenArray()
    {
        $this->assertEquals(
            'anyPattern{token}',
            MockTest::callMockMethod(
                $this->routeFormatter,
                'addTokens',
                [[], 'anyPattern{token}']
            )
        );
    }

    public function testPreparePattern()
    {
        $pattern = 'noNamed1/(?P<token>(noNamed2))\.html';
        $result = MockTest::callMockMethod(
            $this->routeFormatter,
            'preparePattern',
            [$pattern]
        );
        $this->assertEquals(
            '/^noNamed1\/(?P<token>(noNamed2))\.html$/',
            $result
        );
    }

    public function testAddPattern()
    {
        $this->routeFormatter->addPattern('name', 'testPattern');
        $this->assertTrue(
            in_array(
                'testPattern',
                $this->routeFormatter->getPatternArray()
            )
        );
    }

    public function testAddPatterns()
    {
        $patterns = [
            'name1' => 'testPattern1',
            'name2' => 'testPattern2',
            'name3' => 'testPattern3',
        ];
        $this->routeFormatter->addPatterns($patterns);
        $patternArray = $this->routeFormatter->getPatternArray();
        $this->assertTrue(
            in_array('testPattern1', $patternArray) &&
            in_array('testPattern2', $patternArray) &&
            in_array('testPattern3', $patternArray)
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidRouteWithBrokenRoute()
    {
        $route = 'route/@pattern/{token}/';
        MockTest::callMockMethod(
            $this->routeFormatter,
            'validRoute',
            [$route]
        );
    }

    public function testValidRouteWithRightRoute()
    {
        $route = 'route/route2/';
        $this->assertTrue(
            MockTest::callMockMethod(
                $this->routeFormatter,
                'validRoute',
                [$route]
            )
        );
    }

    public function testFormat()
    {
        $route = [
            'name1' => [
                'pattern' => 'route/@digit/{globalToken}/'
            ],
            'name2' => [
                'pattern' => 'route2/{localToken}/{globalToken}/',
                'token' => [
                    'localToken' => 'anyPattern',
                    'globalToken' => 'overrideGlobalToken'
                ]
            ]
        ];
        $token = [
            'globalToken' => 'globalPattern'
        ];
        $this->routeFormatter = new RouteFormatter(
            $this->mockRoute($route, $token)
        );

        $this->routeFormatter->format();

        $this->assertEquals(
            [
                'name1' => [
                    'pattern' => '/^route\/(\d+)\/(?P<globalToken>globalPattern)\/$/'
                ],
                'name2' => [
                    'pattern' => '/^route2\/(?P<localToken>anyPattern)\/(?P<globalToken>overrideGlobalToken)\/$/',
                    'token' => [
                        'localToken' => 'anyPattern',
                        'globalToken' => 'overrideGlobalToken'
                    ]
                ]
            ],
            $this->routeFormatter->getRouteArray()
        );
    }

    private function mockRoute(array $route = [], array $token = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Route')->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        $stub->method('getTokenArray')->willReturn($token);
        return $stub;
    }

    private function mockHost(string $query)
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Conf\Host')->getMock();
        $stub->method('getQueryString')->willReturn($query);
        MockTest::inject(Conf::instance(), 'host', $stub);
    }
}
