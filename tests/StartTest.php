<?php

namespace Test\Controller;

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
        $parser = \PHPUnit_Framework_Assert::readAttribute(
            $this->start,
            'parser'
        );
        $this->assertInstanceOf('Ignaszak\Router\Conf\Conf', $conf);
        $this->assertInstanceOf('Ignaszak\Router\Route', $route);
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\Parser',
            $parser
        );
    }

    public function testSetConfiguration()
    {
        $stub = $this->getMockBuilder('Conf')
            ->setMethods(['setProperty'])
            ->getMock();
        $stub->expects($this->once())->method('setProperty');
        MockTest::inject($this->start, 'conf', $stub);
        $this->start->anyConfProperty = 'value';
    }

    public function testAdd()
    {
        $stub = $this->getMockBuilder('Route')
            ->setMethods(['add'])
            ->getMock();
        $stub->expects($this->once())->method('add');
        MockTest::inject($this->start, 'route', $stub);
        $this->start->add('name', 'pattern');
    }

    public function testAddToken()
    {
        $stub = $this->getMockBuilder('Route')
            ->setMethods(['addToken'])
            ->getMock();
        $stub->expects($this->once())->method('addToken');
        MockTest::inject($this->start, 'route', $stub);
        $this->start->addToken('token', 'pattern');
    }

    public function testRun()
    {
        $stub = $this->getMockBuilder('Route')
            ->setMethods(['add', 'sort'])
            ->getMock();
        $stub->expects($this->once())->method('add');
        $stub->expects($this->once())->method('sort');
        MockTest::inject($this->start, 'route', $stub);

        $stub = $this->getMockBuilder('Parser')
            ->setMethods(['run'])
            ->getMock();
        $stub->expects($this->once())->method('run');
        MockTest::inject($this->start, 'parser', $stub);

        $this->start->run();
    }
}
