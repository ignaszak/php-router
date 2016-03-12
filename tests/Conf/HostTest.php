<?php
namespace Test\Conf;

use Ignaszak\Router\Conf\Host;
use Test\Mock\MockTest;

class HostTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Host
     */
    private $host;

    public function setUp()
    {
        $this->host = new Host();
    }

    public function testGetBaseURIFromServerName()
    {
        @$_SERVER['SERVER_NAME'] = 'baseuri.com';
        $this->assertEquals(
            'http://baseuri.com/',
            MockTest::callMockMethod($this->host, 'getBaseURIFromServerName')
        );
    }

    public function testValidEmptyBaseURI()
    {
        @$_SERVER['SERVER_NAME'] = 'baseuri.com';
        $this->host->validBaseURI('');
        $this->assertEquals(
            'http://baseuri.com/',
            $this->host->getBaseURI()
        );
        $this->assertEmpty(
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'base')
        );
    }

    public function testValidBaseURIWithProtocol()
    {
        $this->host->validBaseURI('http://www.baseURI.com');
        $this->assertEquals(
            'http://www.baseURI.com/',
            $this->host->getBaseURI()
        );
        $this->assertEquals(
            'baseURI.com/',
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'base')
        );
    }

    public function testValidBaseURIWithoutProtocol()
    {
        $this->host->validBaseURI('www.baseURI.com');
        $this->assertEquals(
            'www.baseURI.com/',
            $this->host->getBaseURI()
        );
        $this->assertEquals(
            'baseURI.com/',
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'base')
        );
    }

    public function testGetQueryStringWithBaseFolder()
    {
        @$_SERVER['SERVER_NAME'] = 'www.baseURI.com';
        @$_SERVER['REQUEST_URI'] = '/baseFolder/request1/request2';
        $this->host->validBaseURI('www.baseURI.com/baseFolder');

        $this->assertEquals(
            'request1/request2',
            $this->host->getQueryString()
        );
    }

    public function testGetQueryString()
    {
        @$_SERVER['SERVER_NAME'] = 'www.baseURI.com';
        @$_SERVER['REQUEST_URI'] = '/baseFolder/request1/request2';
        $this->host->validBaseURI('www.baseURI.com');

        $this->assertEquals(
            'baseFolder/request1/request2',
            $this->host->getQueryString()
        );
    }

    public function testEmptyBaseRequestURI()
    {
        $this->assertEmpty(
            MockTest::callMockMethod($this->host, 'baseRequestURI')
        );
    }

    public function testBaseRequestURI()
    {
        @$_SERVER['SERVER_NAME'] = 'www.baseURI.com';
        $this->host->validBaseURI('www.baseURI.com/baseFolder');
        $this->assertEquals(
            '/baseFolder/',
            MockTest::callMockMethod($this->host, 'baseRequestURI')
        );
    }

    public function testReplaceURI()
    {
        $uri = 'www.baseURI.com';
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('baseURI.com', $replace);

        $uri = 'http://www.baseURI.com';
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('baseURI.com', $replace);

        $uri = 'https://www.baseURI.com';
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('baseURI.com', $replace);

        $uri = '127.0.0.1';
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('localhost', $replace);

        $uri = '192.168.1.1'; // Any last number
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('localhost', $replace);

        $uri = '192.168.1.2'; // Any last number
        $replace = MockTest::callMockMethod($this->host, 'replaceURI', [$uri]);
        $this->assertEquals('localhost', $replace);
    }

    public function testAddSlashToURI()
    {
        $uri = 'www.baseURI.com';
        $replace = MockTest::callMockMethod(
            $this->host,
            'addSlashToURI',
            [$uri]
        );
        $this->assertEquals('www.baseURI.com/', $replace);

        $uri = 'www.baseURI.com/';
        $replace = MockTest::callMockMethod(
            $this->host,
            'addSlashToURI',
            [$uri]
        );
        $this->assertEquals('www.baseURI.com/', $replace);
    }
}
