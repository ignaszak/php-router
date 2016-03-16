<?php
namespace Test\Conf;

use Ignaszak\Router\Conf\Host;

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

    public function testSetBaseURL()
    {
        @$_SERVER['SERVER_NAME'] = 'test.com';
        $this->host = new Host('/baseQuery');
        $this->assertEquals(
            'http://test.com/baseQuery',
            $this->host->getBaseURL()
        );

        @$_SERVER['HTTPS'] = true;
        $this->assertEquals(
            'https://test.com/baseQuery',
            $this->host->getBaseURL()
        );
    }

    public function testGetBaseURIFromServerName()
    {
        @$_SERVER['SERVER_NAME'] = 'anybaseurl';
        $this->assertEquals('http://anybaseurl', $this->host->getBaseURL());
    }

    public function testGetQueryFromRequestURI()
    {
        @$_SERVER['REQUEST_URI'] = '/baseQuery/quey1/query2';
        $this->assertEquals('/baseQuery/quey1/query2', $this->host->getQuery());
    }

    public function testGetQueryWithDefinedBaseURI()
    {
        @$_SERVER['SERVER_NAME'] = 'baseurl.com';
        @$_SERVER['REQUEST_URI'] = '/baseQuery/quey1/query2';
        $this->host = new Host('/baseQuery');
        $this->assertEquals('/quey1/query2', $this->host->getQuery());
    }

    public function testGetHttpMethod()
    {
        @$_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $this->host->getHttpMethod());
    }
}
