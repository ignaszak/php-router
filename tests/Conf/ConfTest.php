<?php
namespace Test\Conf;

use Ignaszak\Router\Conf\Conf;
use Test\Mock\MockTest;

class ConfTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Conf
     */
    private $conf;

    public function setUp()
    {
        $this->conf = Conf::instance();
        @$_SERVER['HTTPS'] == 'off';
        @$_SERVER['SERVER_NAME'] = '';
        @$_SERVER['REQUEST_URI'] = '';
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Conf\Host',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->conf,
                'host'
            )
        );
    }

    public function testSetAndGetProperty()
    {
        $this->conf->setProperty('baseURI', 'anyURI');
        $this->conf->setProperty('defaultRoute', 'anyDefaultRoute');
        $this->assertEquals('anyURI', Conf::get('baseURI'));
        $this->assertEquals('anyDefaultRoute', Conf::get('defaultRoute'));
    }

    public function testGetQueryString()
    {
        $stub = $this->getMockBuilder('Host')
            ->setMethods(['validBaseURI', 'getQueryString'])
            ->getMock();
        $stub->expects($this->once())->method('validBaseURI');
        $stub->expects($this->once())->method('getQueryString')->willReturn('');
        MockTest::inject($this->conf, 'host', $stub);
        $this->conf->getQueryString();
    }
}
