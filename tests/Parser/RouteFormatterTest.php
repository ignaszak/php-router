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
            'Ignaszak\Router\Route',
            $this->routeFormatter->getRoute()
        );
    }

    public function testAddRouteTokens()
    {
        $route = [
            'pattern' => '/{token1}(anyPattern)/{token2}.{format}',
            'token' => [
                'token1' => 'pattern1',
                'token2' => 'pattern2',
                'format' => 'pattern3'
            ]
        ];
        $result = MockTest::callMockMethod(
            $this->routeFormatter,
            'addTokensToRoute',
            [$route['token'], $route['pattern']]
        );
        $this->assertEquals(
            '/(?P<token1>pattern1)(anyPattern)/(?P<token2>pattern2).(?P<format>pattern3)',
            $result
        );
    }

    public function testAddEmptyTokenArray()
    {
        $this->assertEquals(
            '/anyPattern{token}',
            MockTest::callMockMethod(
                $this->routeFormatter,
                'addTokensToRoute',
                [[], '/anyPattern{token}']
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

    public function testAddToken()
    {
        $this->routeFormatter->addToken('name1', 'token1');
        $this->routeFormatter->addToken('name2', 'token2');
        $this->assertEquals(
            [
                'name1' => 'token1',
                'name2' => 'token2'
            ],
            $this->routeFormatter->getTokenArray()
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
            $this->routeFormatter->getTokenArray()
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
        $route = '/route/@pattern/{token}/';
        MockTest::callMockMethod(
            $this->routeFormatter,
            'validRoute',
            [$route]
        );
    }

    public function testValidRouteWithRightRoute()
    {
        $route = '/route/route2/';
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
                'pattern' => '/route/@digit/{globalToken}/'
            ],
            'name2' => [
                'pattern' => '/route2/{localToken}/{globalToken}/',
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
            $this->mockRoute($route)
        );
        MockTest::inject($this->routeFormatter, 'tokenArray', $token);

        $this->routeFormatter->format();

        $this->assertEquals(
            [
                'name1' => [
                    'pattern' => '/^\/route\/(\d+)\/(?P<globalToken>globalPattern)\/$/'
                ],
                'name2' => [
                    'pattern' => '/^\/route2\/(?P<localToken>anyPattern)\/(?P<globalToken>overrideGlobalToken)\/$/',
                    'token' => [
                        'localToken' => 'anyPattern',
                        'globalToken' => 'overrideGlobalToken'
                    ]
                ]
            ],
            $this->routeFormatter->getRouteArray()
        );
    }

    public function testSort()
    {
        MockTest::inject($this->routeFormatter, 'routeArray', [
            'name2' => [
                'pattern' => '/pattern',
                'group' => ''
            ],
            'name1' => [
                'pattern' => '/pattern/subpattern',
                'group' => ''
            ]
        ]);
        $this->routeFormatter->sort();
        $this->assertEquals(
            [
                'name1' => [
                    'pattern' => '/pattern/subpattern',
                    'group' => ''
                ],
                'name2' => [
                    'pattern' => '/pattern',
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
