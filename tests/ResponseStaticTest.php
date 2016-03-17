<?php
namespace Test;

use Ignaszak\Router\ResponseStatic;

class ResponseStaticTest extends \PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $this->mockResponse('getName');
        ResponseStatic::getName();
    }

    public function testGetController()
    {
        $this->mockResponse('getController');
        ResponseStatic::getController();
    }

    public function testGetAttachment()
    {
        $this->mockResponse('getAttachment');
        ResponseStatic::getAttachment();
    }

    public function testGetParams()
    {
        $this->mockResponse('getParams');
        ResponseStatic::getParams();
    }

    public function testGetParam()
    {
        $this->mockResponse('getParam');
        ResponseStatic::getParam('anyParam');
    }

    public function testGetGroup()
    {
        $this->mockResponse('getGroup');
        ResponseStatic::getGroup();
    }

    public function testGetLink()
    {
        $this->mockResponse('getLink');
        ResponseStatic::getLink('name', []);
    }

    private function mockResponse(string $method)
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Response')
            ->disableOriginalConstructor()
            ->setMethods([$method])
            ->getMock();
        $stub->expects($this->once())->method($method);
        ResponseStatic::$response = $stub;
    }
}
