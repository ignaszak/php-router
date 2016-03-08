<?php

namespace Test\Controller;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
use Ignaszak\Router\Route;
use Ignaszak\Router\Conf\Conf;

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

    public function testAddTokenToRoute()
    {
        $route = 'post/{page}/{alias:anyAlias}.{format}';
        $token = [
            'page' => '([0-9]*)',
            'format' => '(html|xml)'
        ];
        $this->parser = new Parser($this->mockRoute([], $token));
        $this->assertEquals(
            'post/{page:([0-9]*)}/{alias:anyAlias}.{format:(html|xml)}',
            MockTest::callMockMethod($this->parser, 'addTokenToRoute', [$route])
        );
    }

    public function testPrepareRoute()
    {
        $route = 'post/{page:([0-9]*)}/{alias:anyAlias}.{format:(html|xml)}';
        $this->assertEquals(
            '/(?P<route1>post)\/(?P<page>([0-9]*))\/(?P<alias>anyAlias)\.(?P<format>(html|xml))/',
            MockTest::callMockMethod($this->parser, 'prepareRoute', [$route])
        );
    }

    public function testParse()
    {
        $this->mockHost('post/1/anyAlias.html');
        $result = '/(?P<route1>post)\/(?P<page>([0-9]*))\/(?P<alias>anyAlias)\.(?P<format>(html|xml))/';
        $parse = MockTest::callMockMethod($this->parser, 'parse', [$result]);
        $this->assertEquals(
            [
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
            ],
            $parse
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

    public function testRun()
    {
        $route = [
            [
                'name' => 'name1',
                'pattern' => 'firstRoute/{token}/{definedToken:[a-z]*}/'
            ]
        ];
        $token = [
            'token' => 'anyToken'
        ];
        $this->mockHost('firstRoute/anyToken/any/');
        $this->parser = new Parser($this->mockRoute($route, $token));
        $this->parser->run();
        $this->assertEquals(
            [
                'name' => 'name1',
                'route1' => 'firstRoute',
                'token' => 'anyToken',
                'definedToken' => 'any'
            ],
            Route::$request
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
