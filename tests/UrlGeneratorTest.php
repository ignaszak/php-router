<?php
/**
 *
 * PHP Version 7.0
 *
 * @copyright 2016 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Test;

use Ignaszak\Router\UrlGenerator;
use Test\Mock\MockTest;

/**
 * Class UrlGeneratorTest
 * @package Test
 */
class UrlGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    public function setUp()
    {
        $this->urlGenerator = new UrlGenerator($this->mockRoute());
    }

    public function testConstructorSetRouteArray()
    {
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute(['anyRouteArray'])
        );
        $this->assertEquals(
            ['anyRouteArray'],
            \PHPUnit_Framework_Assert::readAttribute(
                $this->urlGenerator,
                'convertedRouteArray'
            )
        );
    }

    public function testConstructorSetHost()
    {
        $host = $this->getMockBuilder('Ignaszak\Router\Host')
            ->disableOriginalConstructor()->getMock();
        $host->method('getBaseUrl')->willReturn('anyBaseUrl');
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute(),
            $host
        );
        $this->assertEquals(
            'anyBaseUrl',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->urlGenerator,
                'baseUrl'
            )
        );
    }

    public function testConstructorSetEmptyHost()
    {
        $this->assertEmpty(
            \PHPUnit_Framework_Assert::readAttribute(
                $this->urlGenerator,
                'baseUrl'
            )
        );
    }

    public function testUrl()
    {
        $convertedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ]
            ]
        ];
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute($convertedRouteArray)
        );
        $this->assertEquals(
            '/test/anyalias.html',
            $this->urlGenerator->url('name', [
                'alias' => 'anyalias',
                'format' => 'html'
            ])
        );
    }

    public function testUrlWithDefaultTokenValue()
    {
        $convertedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ],
                'defaults' => [
                    'alias' => 'anyAlias'
                ]
            ]
        ];
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute($convertedRouteArray)
        );
        $this->assertEquals(
            '/test/anyAlias.html',
            $this->urlGenerator->url('name', [
                'format' => 'html'
            ])
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testUrlWithIncorrectDefaultTokenValue()
    {
        $convertedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\d+)',
                    'format' => '(html|xml|json)'
                ],
                'defaults' => [
                    'alias' => 'anyAlias'
                ]
            ]
        ];
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute($convertedRouteArray)
        );
        $this->urlGenerator->url('name', [
            'format' => 'html'
        ]);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testGetLinkWithInvalidValue()
    {
        $convertedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ]
            ]
        ];
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute($convertedRouteArray)
        );
        $this->urlGenerator->url('name', [
            'alias' => 'ANYALIAS',
            'format' => 'doc'
        ]);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testUrlWithEmptyReplacement()
    {
        $convertedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ]
            ]
        ];
        $this->urlGenerator = new UrlGenerator(
            $this->mockRoute($convertedRouteArray)
        );
        $this->urlGenerator->url('name', [
            'alias' => 'anyalias'
        ]);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testInvalidRouteName()
    {
        $this->urlGenerator = new UrlGenerator($this->mockRoute());
        $this->urlGenerator->url('name', []);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testInvalidLink()
    {
        MockTest::callMockMethod($this->urlGenerator, 'validLink', [
            '/anyLink/with/unreplaced/(?P<token>([a-z]+))/',
            'routName'
        ]);
    }

    public function testValidLink()
    {
        $this->assertTrue(
            MockTest::callMockMethod($this->urlGenerator, 'validLink', [
                '/anyLink/with/replaced/token/',
                'routName'
            ])
        );
    }

    private function mockRoute(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Collection\IRoute')
            ->disableOriginalConstructor()->getMock();
        $stub->method('getRouteArray')->willReturn($route);

        return $stub;
    }
}
