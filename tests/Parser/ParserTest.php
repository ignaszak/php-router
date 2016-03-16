<?php
namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
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

    public function testRun()
    {
        $formatedRoute = [
            'name' => [
                'pattern' => '/^\/firstRoute\/(?P<token>anyPattern)\/$/',
                'group' => ''
            ]
        ];
        $this->parser = new Parser($this->mockRoute($formatedRoute));
        $this->parser->run($this->mockHost('/firstRoute/anyPattern/'));
        $this->assertEquals(
            [
                'name' => 'name',
                'controller' => '',
                'callAttachment' => '',
                'attachment' => '',
                'routes' => [
                    'token' => 'anyPattern'
                ],
                'group' => ''
            ],
            IRouteParser::$request
        );
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
            'routes' => [
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
        $this->parser->run();
        $this->assertEmpty(IRouteParser::$request);
    }

    public function testFormatArray()
    {
        $array = [
            0 => ['/attach/name/string/post/1234/', 0],
            'name' => ['name', 7],
            1 => ['name', 7],
            2 => ['name', 7],
            3 => ['string', 12],
            'post' => ['post', 19],
            4 => ['post', 19],
            5 => ['post', 19],
            6 => [1234, 24]
        ];
        $result = MockTest::callMockMethod(
            $this->parser,
            'formatArray',
            [$array]
        );
        $this->assertEquals(
            [
                'name' => 'name',
                0 => 'string',
                'post' => 'post',
                1 => 1234
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

    private function mockRoute(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Interfaces\IRouteParser')
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
