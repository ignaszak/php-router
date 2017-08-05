<?php
namespace Test\Collection;

use Ignaszak\Router\Collection\Route;
use Test\Mock\MockTest;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Route
     */
    private $route;

    public function setUp()
    {
        $this->route = Route::start();
    }

    public function testAddWithName()
    {
        $this->route->add('name1', '/pattern/subpattern', 'AnyHttpMethod');
        $this->assertEquals(
            'name1',
            $this->get('lastName')
        );
        $this->route->add('name2', '/pattern');
        $this->assertEquals(
            'name2',
            $this->get('lastName')
        );

        $this->assertEquals(
            [
                'name1' => [
                    'path' => '/pattern/subpattern',
                    'group' => '',
                    'method' => 'AnyHttpMethod'
                ],
                'name2' => [
                    'path' => '/pattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->get('routesArray')
        );
    }

    public function testAddWithoutNAme()
    {
        $this->route->add(null, '/pattern/subpattern');
        $this->assertEquals(0, $this->get('lastName'));
        $this->route->add(null, '/pattern');
        $this->assertEquals(1, $this->get('lastName'));

        $this->assertEquals(
            [
                0 => [
                    'path' => '/pattern/subpattern',
                    'group' => '',
                    'method' => ''
                ],
                1 => [
                    'path' => '/pattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->get('routesArray')
        );
    }

    public function testGet()
    {
        $this->route->get(null, '/pattern/subpattern');
        $this->assertEquals(
            'GET',
            $this->get('routesArray')[0]['method']
        );
    }

    public function testPost()
    {
        $this->route->post(null, '/pattern/subpattern');
        $this->assertEquals(
            'POST',
            $this->get('routesArray')[0]['method']
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddDuplicateName()
    {
        $this->route->add('name', '/anyPattern');
        $this->route->add('name', '/anyPattern');
    }

    public function testAddController()
    {
        $this->route->add('anyName', '/anyPattern')->controller('anyController');
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'controller' => 'anyController',
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->get('routesArray')
        );
    }

    public function testAddTokensToRoute()
    {
        $this->route->add('anyName', '/anyPattern')->tokens([
            'tokenName1' => 'pattern1',
            'tokenName2' => 'pattern2',
            'tokenName3' => 'pattern3'
        ]);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'tokens' => [
                        'tokenName1' => 'pattern1',
                        'tokenName2' => 'pattern2',
                        'tokenName3' => 'pattern3'
                    ],
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->get('routesArray')
        );
    }

    public function testDefaultsTokensValues()
    {
        $this->route->add('anyName', '/anyPattern')->tokens([
            'token1' => '\d+'
        ])->defaults([
            'token1' => 1
        ]);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'tokens' => [
                        'token1' => '\d+'
                    ],
                    'defaults' => [
                        'token1' => 1
                    ],
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->get('routesArray')
        );
    }

    public function testAttach()
    {
        $anyAttachment = function () {};
        $this->route->add('anyName', '/anyPattern')->attach($anyAttachment);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'attachment' => $anyAttachment,
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->get('routesArray')
        );
    }

    public function testCallableAttach()
    {
        $anyAttachment = function () {};
        $this->route->add('anyName', '/anyPattern')
            ->attach($anyAttachment);
        $this->assertEquals(
            [
                'anyName' => [
                    'path' => '/anyPattern',
                    'attachment' => $anyAttachment,
                    'group' => '',
                    'method' => ''
                ],
            ],
            $this->get('routesArray')
        );
    }

    public function testGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, '/anyPattern');
        $this->route->add(null, '/anyPattern');
        $this->assertEquals(
            [
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ],
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ]
            ],
            $this->get('routesArray')
        );
    }

    public function testClearGroup()
    {
        $this->route->group('anyGroupName');
        $this->route->add(null, '/anyPattern');
        $this->route->group();
        $this->route->add(null, '/anyPattern');
        $this->assertEquals(
            [
                [
                    'path' => '/anyPattern',
                    'group' => 'anyGroupName',
                    'method' => ''
                ],
                [
                    'path' => '/anyPattern',
                    'group' => '',
                    'method' => ''
                ]
            ],
            $this->get('routesArray')
        );
    }

    public function testAddTokens()
    {
        $tokens = [
            'tokenName1' => 'pattern1',
            'tokenName2' => 'pattern2',
            'tokenName3' => 'pattern3'
        ];
        $this->route->addTokens($tokens);
        $this->assertEquals(
            $tokens,
            $this->get('tokensArray')
        );
    }

    public function testAddDefaults()
    {
        $defaults = [
            'tokenName1' => 1,
            'tokenName2' => 2,
            'tokenName3' => 3
        ];
        $this->route->addDefaults($defaults);
        $this->assertEquals(
            $defaults,
            $this->get('defaultsArray')
        );
    }

    public function testAddPatterns()
    {
        $patterns = [
            'name1' => 'testPattern1',
            'name2' => 'testPattern2',
            'name3' => 'testPattern3',
        ];
        $this->route->addPatterns($patterns);
        $this->assertTrue(
            in_array('testPattern1', $this->get('patternsArray')) &&
            in_array('testPattern2', $this->get('patternsArray')) &&
            in_array('testPattern3', $this->get('patternsArray'))
        );
    }

    public function testGetRouteArray()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Matcher\Converter')
            ->disableOriginalConstructor()->getMock();
        $stub->expects($this->once())->method('convert')->willReturn([]);
        MockTest::inject($this->route, 'converter', $stub);
        $this->route->getRouteArray();
    }

    public function testGetChecksum()
    {
        $this->route->add(null, 'anypattern')->tokens(['token' => 'pattern']);
        $this->route->addTokens(['token' => 'pattern']);
        $this->route->addPatterns(['pattern' => 'regex']);
        $this->assertEquals(
            md5(json_encode([
                'routes' => [
                    0 => [
                        'path' => 'anypattern',
                        'group' => '',
                        'method' => '',
                        'tokens' => [
                            'token' => 'pattern'
                        ]
                    ]
                ],
                'tokens' => [
                    'token' => 'pattern'
                ],
                'patterns' => [
                    'pattern' => 'regex'
                ]
            ])),
            $this->route->getChecksum()
        );
    }

    private function get(string $property)
    {
        return \PHPUnit_Framework_Assert::readAttribute(
            $this->route,
            $property
        );
    }
}
