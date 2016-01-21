<?php

namespace Test;

class HostTest extends \PHPUnit_Framework_TestCase
{

    private $_host;

    public function setHost($baseURL = '')
    {
        $this->_host = new \Ignaszak\Router\Host($baseURL);
    }

    public function testConstruct()
    {
        $this->setHost('http://www.site.com');
        $baseURL = \PHPUnit_Framework_Assert::readAttribute($this->_host, 'baseURL');
        $this->assertEquals('site.com/', $baseURL);

        $this->setHost();
        $baseURL = \PHPUnit_Framework_Assert::readAttribute($this->_host, 'baseURL');
        $this->assertEquals(null, $baseURL);
    }

    public function testGetQueryString()
    {
        $requestURI = '/path/query';
        $_SERVER['REQUEST_URI'] = $requestURI;
        $_SERVER['SERVER_NAME'] = 'www.site.com';
        $this->setHost('http://www.site.com/path');
        $queryString = $this->_host->getQueryString();

        $this->assertEquals('query', $queryString);
    }
}
