<?php

namespace Test;

use Ignaszak\Router\Start;
use Test\Mock\MockTest;

class StartTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Start
     */
    private $start;

    public function setUp()
    {
        $this->start = Start::instance();
    }

    public function testConstructor()
    {
        $conf = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'conf'
        );
        $route = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'route'
        );
        $formatter = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'formatter'
        );
        $parser = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'parser'
        );
        $link = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'link'
        );
        $this->assertInstanceOf('Ignaszak\Router\Conf\Conf', $conf);
        $this->assertInstanceOf('Ignaszak\Router\Route', $route);
        $this->assertInstanceOf(
            'Ignaszak\Router\Interfaces\IFormatterStart',
            $formatter
        );
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\Parser',
            $parser
        );
        $this->assertInstanceOf('Ignaszak\Router\Link', $link);
    }

    public function testSetBaseURI()
    {
        $stub = $this->getMockBuilder('Conf')->getMock();
        $stub->baseURI = false;
        MockTest::inject($this->start, 'conf', $stub);
        $this->start->baseURI = 'anyBaseUrl';
        $this->assertEquals('anyBaseUrl', $stub->baseURI);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidProperty()
    {
        $stub = $this->getMockBuilder('Conf')->getMock();
        MockTest::inject($this->start, 'conf', $stub);
        $this->start->invalidProperty = 'anyValue';
    }

    public function testAdd()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Route')
            ->setMethods(['add'])
            ->getMock();
        $stub->expects($this->once())->method('add');
        MockTest::inject($this->start, 'route', $stub);
        $this->start->add('name', 'pattern');
    }

    public function testAddToken()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Route')
            ->setMethods(['addToken'])
            ->getMock();
        $stub->expects($this->once())->method('addToken');
        MockTest::inject($this->start, 'route', $stub);
        $this->start->addToken('token', 'pattern');
    }

    public function testAddPattern()
    {
        $stub = $this->getMockBuilder(
            'Ignaszak\Router\Parser\RouteFormatter'
        )->disableOriginalConstructor()->setMethods(['addPattern'])->getMock();
        $stub->expects($this->once())->method('addPattern');
        MockTest::inject($this->start, 'formatter', $stub);
        $this->start->addPattern('name', 'pattern');
    }

    public function testRun()
    {
        $stub = $this->getMockBuilder('Route')
            ->setMethods(['add', 'sort'])
            ->getMock();
        $stub->expects($this->once())->method('sort');
        MockTest::inject($this->start, 'route', $stub);

        $stub = $this->getMockBuilder('Formatter')
        ->setMethods(['format'])
        ->getMock();
        $stub->expects($this->once())->method('format');
        MockTest::inject($this->start, 'formatter', $stub);

        $stub = $this->getMockBuilder('Parser')
            ->setMethods(['run'])
            ->getMock();
        $stub->expects($this->once())->method('run');
        MockTest::inject($this->start, 'parser', $stub);

        $this->start->run();
    }
}
