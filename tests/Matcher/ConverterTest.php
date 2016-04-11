<?php
namespace Test\Matcher;

use Ignaszak\Router\Matcher\Converter;
use Ignaszak\Router\Matcher\Matcher;
use Test\Mock\MockTest;

class ConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Converter
     */
    private $converter;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    public function testConstructor()
    {
        MockTest::inject(
            $this->converter,
            'routeArray',
            ['anyRouteArray']
        );
        $this->assertEquals(
            ['anyRouteArray'],
            \PHPUnit_Framework_Assert::readAttribute(
                $this->converter,
                'routeArray'
            )
        );
    }

    public function testPreparePattern()
    {
        $pattern = '/noNamed1/(?P<token>(noNamed2))\.html';
        $result = MockTest::callMockMethod(
            $this->converter,
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
            $this->converter,
            'validPattern',
            [$route]
        );
    }

    public function testValidRouteWithRightRoute()
    {
        $route = '/route/route2/';
        $this->assertTrue(
            MockTest::callMockMethod(
                $this->converter,
                'validPattern',
                [$route]
            )
        );
    }

    public function testTransformToRegex()
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
        MockTest::inject(
            $this->converter,
            'routeArray',
            $routeArray
        );
        MockTest::callMockMethod(
            $this->converter,
            'transformToRegex'
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
                $this->converter,
                'convertedRouteArray'
            )
        );
    }

    public function testSort()
    {
        MockTest::inject($this->converter, 'convertedRouteArray', [
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
            $this->converter,
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
            \PHPUnit_Framework_Assert::readAttribute(
                $this->converter,
                'convertedRouteArray'
            )
        );
    }

    public function testGetPatterns()
    {
        MockTest::inject(
            $this->converter,
            'routeArray',
            [
                'patterns' => [
                    'pattern' => 'regex'
                ]
            ]
        );
        $this->assertTrue(
            array_key_exists(
                '@pattern',
                MockTest::callMockMethod(
                    $this->converter,
                    'getPatterns'
                )
            )
        );
    }

    public function testConvert()
    {
        $this->assertEquals(
            [
                'name' => [
                    'path' => '/^\/(?P<token>test1)\/(?P<globalToken>test2)\/test3$/',
                    'tokens' => [
                        'token' => 'test1',
                        'globalToken' => 'test2'
                    ],
                    'route' => '/{token}/{globalToken}/@pattern'
                ]
            ],
            $this->converter->convert([
                'routes' => [
                    'name' => [
                        'path' => '/{token}/{globalToken}/@pattern',
                        'tokens' => [
                            'token' => 'test1'
                        ]
                    ]
                ],
                'tokens' => [
                    'globalToken' => 'test2'
                ],
                'patterns' => [
                    'pattern' => 'test3'
                ]
            ])
        );
    }
}