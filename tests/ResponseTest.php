<?php
namespace Test;

use Ignaszak\Router\Response;
use Test\Mock\MockTest;
use Ignaszak\Router\Link;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    private $response;

    public function setUp()
    {
        $response = [
            'name' => 'name',
            'controller' => 'AnyController',
            'callAttachment' => '',
            'attachment' => '',
            'params' => [
                'token1' => 'value1',
                'token2' => 'value2'
            ],
            'group' => 'anyGroup'
        ];
        $this->response = new Response($response);
    }

    public function testConstructor()
    {
        $this->assertNotEmpty(
            \PHPUnit_Framework_Assert::readAttribute(
                $this->response,
                'response'
            )
        );
    }

    public function testGetName()
    {
        $this->assertEquals('name', $this->response->getName());
    }

    public function testGetIntName()
    {
        $response = ['name' => 0];
        $this->response = new Response($response);
        $this->assertEquals('0', $this->response->getName());
    }

    public function testGetController()
    {
        $this->assertEquals('AnyController', $this->response->getController());
    }

    public function testGetAttachment()
    {
        $response = ['attachment' => function () {
        }];
        $this->response = new Response($response);
        $this->assertInstanceOf('Closure', $this->response->getAttachment());
    }

    public function testGetEmptyAttachment()
    {
        $this->assertInstanceOf('Closure', $this->response->getAttachment());
    }

    public function testGetParams()
    {
        $this->assertEquals([
                'token1' => 'value1',
                'token2' => 'value2'
            ], $this->response->getParams());
    }

    public function testGetParam()
    {
        $this->assertEquals('value1', $this->response->getParam('token1'));
    }

    public function testGetGroup()
    {
        $this->assertEquals('anyGroup', $this->response->getGroup());
    }

    public function testGetLink()
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Link')
        ->disableOriginalConstructor()->setMethods(['getLink'])->getMock();
        $stub->expects($this->once())->method('getLink');
        MockTest::inject(Link::instance(), 'link', $stub);
        $this->response->getLink('name', []);
    }
}
