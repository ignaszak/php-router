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
        @$_SERVER['SERVER_NAME'] = 'http://www.baseURI.com/';
        $this->link = Link::instance($this->mockFormatter([]));
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Ignaszak\Router\Link', Link::instance());
    }
/*
    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Interfaces\IFormatterStart',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->link,
                'formatter'
            )
        );
        $this->assertNotEmpty(
            \PHPUnit_Framework_Assert::readAttribute(
                $this->link,
                'baseURI'
            )
        );
    }

    public function testGetLink()
    {
        $formatedRouteArray = [
            'anyRouteName' => [
                'pattern' => '/^noNamed\/(?P<token>[a-z]*)\/$/'
            ]
        ];
        MockTest::inject(
            $this->link,
            'formatter',
            $this->mockFormatter($formatedRouteArray)
        );
        $this->assertEquals(
            'noNamed/anyToken/',
            $this->link->getLink('anyRouteName', ['token' => 'anyToken'])
        );
    }
*/
    private function mockFormatter(array $routeArray)
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\RouteFormatter')
            ->disableOriginalConstructor()
            ->setMethods(['getRouteArray'])
            ->getMock();
        $stub->method('getRouteArray')->willReturn($routeArray);
        return $stub;
    }
}
