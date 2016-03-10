<?php
namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
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

    public function testRun()
    {
        $formatedRoute = [
            'name' => [
                'pattern' => '/^firstRoute\/(?P<token>anyPattern)\/$/'
            ]
        ];
        $this->mockHost('firstRoute/anyPattern/');
        $this->parser = new Parser($this->mockRoute($formatedRoute));
        $this->parser->run();
        $this->assertEquals(
            [
                'name' => 'name',
                'controller' => '',
                'routes' => [
                    'token' => 'anyPattern'
                ]
            ],
            IRouteParser::$request
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
            0 => 'post/1/anyAlias.html',
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
                'page' => 1,
                'alias' => 'anyAlias',
                'format' => 'html'
            ],
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
        $stub->method('getQueryString')->willReturn($query);
        MockTest::inject(Conf::instance(), 'host', $stub);
    }
}
