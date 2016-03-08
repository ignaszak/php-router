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

    public function testValidEmptyBaseURI()
    {
        $this->host->validBaseURI('');
        $this->assertEmpty(
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'baseURI')
        );
    }

    public function testValidBaseURIWithProtocol()
    {
        $this->host->validBaseURI('http://www.baseURI.com');
        $this->assertEquals(
            'baseURI.com/',
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'baseURI')
        );

        $this->host->validBaseURI('http://www.baseURI.com/');
        $this->assertEquals(
            'baseURI.com/',
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'baseURI')
        );
    }

    public function testValidBaseURIWithoutProtocol()
    {
        $this->host->validBaseURI('www.baseURI.com');
        $this->assertEquals(
            'baseURI.com/',
            \PHPUnit_Framework_Assert::readAttribute($this->host, 'baseURI')
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
