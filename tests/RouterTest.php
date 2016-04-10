<?php
namespace Test;

use Ignaszak\Router\Router;
use Test\Mock\MockTest;

class RouterTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Router
     */
    private $router;

    public function setUp()
    {
        $this->router = new Router($this->mockRoute('anyChecksum'));
    }

    public function testConstructor()
    {
        $route = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'route'
        );
        $parser = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'parser'
        );
        $link = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'link'
        );
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\RouteFormatter',
            $route
        );
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\Parser',
            $parser
        );
        $this->assertInstanceOf('Ignaszak\Router\Link', $link);
    }

    public function testConstructorWithCache()
    {
        $mock = $this->mockRoute();
        $this->router = new Router($mock);
        $route = \PHPUnit_Framework_Assert::readAttribute(
            $this->router,
            'route'
        );
        $this->assertInstanceOf(
            get_class($mock),
            $route
        );
    }

    public function testRun()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\Parser')
            ->disableOriginalConstructor()
            ->setMethods(['run'])
            ->getMock();
        $stub->expects($this->once())->method('run')->willReturn([]);
        MockTest::inject($this->router, 'parser', $stub);

        $stub = $this->getMockBuilder('Ignaszak\Router\Conf\Host')->getMock();
        $response = $this->router->run($stub);
        $this->assertInstanceOf('Ignaszak\Router\Response', $response);
    }

    private function mockRoute(string $checksum = '', array $route = [])
    {
        $stub = $this->getMockBuilder("Ignaszak\Router\Collection\IRoute")
            ->disableOriginalConstructor()->getMock();
        $stub->method('getChecksum')->willReturn($checksum);
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }
}
