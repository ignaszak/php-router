<?php

namespace Test\Controller;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
use Ignaszak\Router\Route;
use Ignaszak\Router\Conf\Conf;
use Ignaszak\Router\Interfaces\IRouteParser;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser($this->mockRoute());
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Route',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->parser,
                'route'
            )
        );
    }

    public function testParseNoNamedRoutes()
    {
        $route = [
            'pattern' => 'noNamed1/:token2(noNamed2)/noNamed3/',
            'token' => [
                ':token' => 'anyPattern'
            ]
        ];
        $result = MockTest::callMockMethod(
            $this->parser,
            'parseNoNamedRoutes',
            [$route]
        );
        $this->assertEquals(
            '(?P<route1>noNamed1)/:token2(?P<route2>(noNamed2))/(?P<route3>noNamed3)/',
            $result
        );
    }

    public function testAddRouteTokens()
    {
        $route = [
            'pattern' => ':token1(anyPattern)/:token2.:format',
            'token' => [
                ':token1' => 'pattern1',
                ':token2' => 'pattern2',
                ':format' => 'pattern3'
            ]
        ];
        $result = MockTest::callMockMethod(
            $this->parser,
            'addTokens',
            [$route['token'], $route['pattern']]
        );
        $this->assertEquals(
            '(?P<token1>pattern1)(anyPattern)/(?P<token2>pattern2).(?P<format>pattern3)',
            $result
        );
    }

    public function testPreparePattern()
    {
        $pattern = '(?P<route1>noNamed1)/(?P<token>(noNamed2)).html';
        $result = MockTest::callMockMethod(
            $this->parser,
            'preparePattern',
            [$pattern]
        );
        $this->assertEquals(
            '/(?P<route1>noNamed1)\/(?P<token>(noNamed2))\.html/',
            $result
        );
    }

    public function testRun()
    {
        $route = [
            'name' => [
                'pattern' => 'firstRoute/:token/:routeToken/',
                'token' => [
                    ':routeToken' => 'anyPattern1'
                ]
            ]
        ];
        $token = [
            ':token' => 'anyPattern2'
        ];
        $this->mockHost('firstRoute/anyPattern2/anyPattern1/');
        $this->parser = new Parser($this->mockRoute($route, $token));
        $this->parser->run();
        $this->assertEquals(
            [
                'name' => 'name',
                'route1' => 'firstRoute',
                'token' => 'anyPattern2',
                'routeToken' => 'anyPattern1'
            ],
            IRouteParser::$request
        );
    }

    public function testFormatArray()
    {
        $array = [
            0 => 'post/1/anyAlias.html',
            'route1' => 'post',
            1 => 'post',
            'page' => 1,
            2 => 1,
            3 => 1,
            'alias' => 'anyAlias',
            4 => 'anyAlias',
            'format' => 'html',
            5 => 'html',
            6 => 'html'
        ];
        $result = MockTest::callMockMethod(
            $this->parser,
            'formatArray',
            [$array]
        );
        $this->assertEquals(
            [
                'route1' => 'post',
                'page' => 1,
                'alias' => 'anyAlias',
                'format' => 'html'
            ],
            $result
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
