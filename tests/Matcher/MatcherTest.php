<?php
namespace Test\Matcher;

use Ignaszak\Router\Matcher\Matcher;
use Test\Mock\MockTest;

class MatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Matcher
     */
    private $matcher;

    public function setUp()
    {
        $this->matcher = new Matcher($this->mockRoute());
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Collection\IRoute',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->matcher,
                'route'
            )
        );
    }

    public function testMatchWithAnyHttpMethod()
    {
        $convertedRouteArray = [
            'name' => [
                'path' => '/^\/firstRoute\/(?P<token>anyPattern)\/$/',
                'tokens' => [
                    'token' => 'anyPattern'
                ],
                'group' => ''
            ]
        ];
        $this->matcher = new Matcher($this->mockRoute($convertedRouteArray));
        $response = $this->matcher->match(
            $this->mockHost('/firstRoute/anyPattern/')
        );
        $this->assertEquals(
            [
                'name' => 'name',
                'controller' => '',
                'attachment' => '',
                'params' => [
                    'token' => 'anyPattern'
                ],
                'group' => ''
            ],
            $response
        );
    }

    public function testMatchWithIncorrectHttpMethod()
    {
        $convertedRouteArray = [
            'name' => [
                'path' => '/^\/firstRoute\/(?P<token>anyPattern)\/$/',
                'group' => '',
                'method' => 'POST'
            ]
        ];
        $this->matcher = new Matcher($this->mockRoute($convertedRouteArray));
        $response = $this->matcher->match(
            null,
            '/firstRoute/anyPattern/',
            'GET'
        );
        $this->assertEmpty($response);
    }

    public function testCallAttachment()
    {
        $request = [
            'name' => 'anyRouteName',
            'controller' => '',
            'attachment' => function ($name) {
                define('NAME', $name);
            },
            'params' => [
                'name' => 'Tomek'
            ]
        ];
        MockTest::callMockMethod($this->matcher, 'callAttachment', [$request]);
        $this->assertEquals(
            'Tomek',
            @NAME
        );
    }

    public function testMatchWithNoMatchedRouts()
    {
        $this->assertEmpty($this->matcher->match());
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
            $this->matcher,
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
        $result = MockTest::callMockMethod($this->matcher, 'matchController', [
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
            $this->matcher,
            'httpMethod',
            [$route, $current]
        );
        return $result;
    }

    private function mockHost(string $query)
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Host')->getMock();
        $stub->method('getQuery')->willReturn($query);
        return $stub;
    }

    private function mockRoute(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Collection\IRoute')
            ->disableOriginalConstructor()
            ->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }
}
