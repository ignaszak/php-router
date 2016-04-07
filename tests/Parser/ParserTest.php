<?php
namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;

class ParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Parser
     */
    private $parser;

    public function setUp()
    {
        $this->parser = new Parser($this->mockRouteFormatter());
    }

    public function testRunWithAnyHttpMethod()
    {
        $formatedRoute = [
            'name' => [
                'path' => '/^\/firstRoute\/(?P<token>anyPattern)\/$/',
                'tokens' => [
                    'token' => 'anyPattern'
                ],
                'group' => ''
            ]
        ];
        $this->parser = new Parser($this->mockRouteFormatter($formatedRoute));
        $response = $this->parser->run(
            $this->mockHost('/firstRoute/anyPattern/')
        );
        $this->assertEquals(
            [
                'name' => 'name',
                'controller' => '',
                'callAttachment' => '',
                'attachment' => '',
                'params' => [
                    'token' => 'anyPattern'
                ],
                'group' => ''
            ],
            $response
        );
    }

    public function testRunWithIncorrectHttpMethod()
    {
        $formatedRoute = [
            'name' => [
                'path' => '/^\/firstRoute\/(?P<token>anyPattern)\/$/',
                'group' => '',
                'method' => 'POST'
            ]
        ];
        $this->parser = new Parser($this->mockRouteFormatter($formatedRoute));
        $response = $this->parser->run(null, '/firstRoute/anyPattern/', 'GET');
        $this->assertEmpty($response);
    }

    public function testCallAttachment()
    {
        $request = [
            'name' => 'anyRouteName',
            'controller' => '',
            'callAttachment' => true,
            'attachment' => function ($name) {
                define('NAME', $name);
            },
            'params' => [
                'name' => 'Tomek'
            ]
        ];
        MockTest::callMockMethod($this->parser, 'callAttachment', [$request]);
        $this->assertEquals(
            'Tomek',
            @NAME
        );
    }

    public function testRunWithNoMatchedRouts()
    {
        $this->assertEmpty($this->parser->run());
    }

    public function testCreateParamsArray()
    {
        $matches = [
            0 => '/firstRoute/anyPattern/',
            'token' => 'anyPattern',
            1 => 'anyPattern'
        ];
        $tokens = [
            'token' => 'anyPattern',
            'format' => '(html|xml|json)'
        ];
        $result = MockTest::callMockMethod(
            $this->parser,
            'createParamsArray',
            [$matches, $tokens]
        );
        $this->assertEquals(
            [
                'token' => 'anyPattern',
                'format' => ''
            ],
            $result
        );
    }

    public function testMatchController()
    {
        $controller = '\\Namespace\\{controller}::{action}';
        $routes = [
            'controller' => 'AnyController',
            'action' => 'anyAction'
        ];
        $result = MockTest::callMockMethod($this->parser, 'matchController', [
            $controller,
            $routes
        ]);
        $this->assertEquals(
            '\\Namespace\\AnyController::anyAction',
            $result
        );
    }

    public function testNoDefinedHttpMethodInRoute()
    {
        $this->assertTrue($this->httpMethod('', 'anyHttpMethod'));
    }

    public function testCorrectHttpMethod()
    {
        $this->assertTrue($this->httpMethod('GET|POST', 'GET'));
    }

    public function testIncorrectHttpMethod()
    {
        $this->assertFalse($this->httpMethod('GET|POST', 'DELETE'));
    }

    private function httpMethod(string $route, string $current): bool
    {
        $result = MockTest::callMockMethod(
            $this->parser,
            'httpMethod',
            [$route, $current]
        );
        return $result;
    }

    private function mockRouteFormatter(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\RouteFormatter')
            ->disableOriginalConstructor()
            ->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }

    private function mockHost(string $query)
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Conf\Host')->getMock();
        $stub->method('getQuery')->willReturn($query);
        return $stub;
    }
}
