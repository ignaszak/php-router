<?php
namespace Test;

use Ignaszak\Router\Link;
use Test\Mock\MockTest;

class LinkTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Link
     */
    private $link;

    public function setUp()
    {
        MockTest::injectStatic('Ignaszak\Router\Link', 'link');
        $this->link = Link::instance();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Ignaszak\Router\Link', Link::instance());
    }

    public function testSet()
    {
        $this->link->set($this->mockFormatter());
        $this->assertInstanceOf(
            'Ignaszak\Router\Route',
            \PHPUnit_Framework_Assert::readAttribute($this->link, 'route')
        );
    }

    public function testGetLink()
    {
        $routeArray = [
            'name' => [
                'pattern' => 'route/{alias}.{format}',
                'token' => [
                    'alias' => '[a-z]+'
                ]
            ]
        ];
        $tokenArray = [
            'alias' => '[A-Z]+',
            'format' => '(html|xml)'
        ];
        $this->link->set($this->mockFormatter($routeArray, $tokenArray));
        $this->assertEquals(
            'route/anyalias.html',
            $this->link->getLink('name', [
                'alias' => 'anyalias',
                'format' => 'html'
            ])
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testGetLinkWithInvalidValue()
    {
        $routeArray = [
            'name' => [
                'pattern' => 'route/{alias}.{format}',
                'token' => [
                    'alias' => '[a-z]+'
                ]
            ]
        ];
        $tokenArray = [
            'alias' => '[A-Z]+',
            'format' => '(html|xml)'
        ];
        $this->link->set($this->mockFormatter($routeArray, $tokenArray));
        $this->link->getLink('name', [
                'alias' => 'ANYALIAS',
                'format' => 'html'
        ]);
    }

    public function testReplacePattern()
    {
        $routeArray = [
            'name' => [
                'pattern' => '{alias}',
                'token' => [
                    'alias' => '@alnum'
                ]
            ]
        ];
        $this->link->set($this->mockFormatter(
            $routeArray,
            [],
            ['alnum' => '[a-z0-9]+']
        ));
        $result = MockTest::callMockMethod(
            $this->link,
            'replacePattern',
            ['@alnum']
        );
        $this->assertEquals(
            '[a-z0-9]+',
            $result
        );
    }

    private function mockFormatter(
        array $routeArray = [],
        array $tokenArray = [],
        array $patternArray = []
    ) {
        $route = $this->getMockBuilder('Ignaszak\Router\Route')
            ->disableOriginalConstructor()
            ->setMethods(['getRouteArray', 'getTokenArray'])
            ->getMock();
        $route->method('getRouteArray')->willReturn($routeArray);
        $route->method('getTokenArray')->willReturn($tokenArray);

        $formatter = $this->getMockBuilder(
            'Ignaszak\Router\Interfaces\IFormatterLink'
        )->disableOriginalConstructor()
            ->setMethods(['getRoute', 'getPatternArray'])
            ->getMock();
        $formatter->method('getRoute')->willReturn($route);
        $formatter->method('getPatternArray')->willReturn($patternArray);

        return $formatter;
    }
}