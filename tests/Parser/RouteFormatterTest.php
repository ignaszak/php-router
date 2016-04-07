<?php
namespace Test\Parser;

use Ignaszak\Router\Parser\Parser;
use Test\Mock\MockTest;
use Ignaszak\Router\Route;
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
            'Ignaszak\Router\Interfaces\IRoute',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->routeFormatter,
                'route'
            )
        );
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

    public function testAddTokens()
    {
        $tokens = [
            'tokenName1' => 'pattern1',
            'tokenName2' => 'pattern2',
            'tokenName3' => 'pattern3'
        ];
        $this->routeFormatter->addTokens($tokens);
        $this->assertEquals(
            $tokens,
            \PHPUnit_Framework_Assert::readAttribute(
                $this->routeFormatter,
                'tokenArray'
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
        $patternArray = \PHPUnit_Framework_Assert::readAttribute(
            $this->routeFormatter,
            'patternArray'
        );
        $this->assertTrue(
            in_array('testPattern1', $patternArray) &&
            in_array('testPattern2', $patternArray) &&
            in_array('testPattern3', $patternArray)
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
            'name' => [
                'path' => '/test/{token1}/@digit/{globaltoken}',
                'tokens' => [
                    'token1' => '(\w+)'
                ]
            ]
        ];
        $tokenArray = [
            'token1' => '([a-z]+)',
            'globaltoken' => '@alnum'
        ];
        $this->routeFormatter = new RouteFormatter(
            $this->mockRoute($routeArray)
        );
        MockTest::inject($this->routeFormatter, 'tokenArray', $tokenArray);
        $this->routeFormatter->format();
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
        $this->routeFormatter->sort();
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

    private function mockRoute(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Route')
            ->disableOriginalConstructor()->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }
}
