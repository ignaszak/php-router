<?php
namespace Test;

use Ignaszak\Router\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    private $response;

    public function setUp()
    {
        $response = [
            'name' => 'name',
            'controller' => 'AnyController',
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

    public function testName()
    {
        $this->assertEquals('name', $this->response->name());
    }

    public function testIntName()
    {
        $response = ['name' => 0];
        $this->response = new Response($response);
        $this->assertEquals('0', $this->response->name());
    }

    public function testController()
    {
        $this->assertEquals('AnyController', $this->response->controller());
    }

    public function testAttachment()
    {
        $response = ['attachment' => function () {
        }];
        $this->response = new Response($response);
        $this->assertInstanceOf('Closure', $this->response->attachment());
    }

    public function testEmptyAttachment()
    {
        $this->assertInstanceOf('Closure', $this->response->attachment());
    }

    public function testAll()
    {
        $this->assertEquals(
            [
                'token1' => 'value1',
                'token2' => 'value2'
            ],
            $this->response->all()
        );
    }

    public function testGet()
    {
        $this->assertEquals('value1', $this->response->get('token1'));
    }

    public function testGetDefault()
    {
        $this->assertEquals(
            'value1',
            $this->response->get('token1', 'default')
        );
    }

    public function testGetDefaultWitNoExistingToken()
    {
        $this->assertEquals(
            'default',
            $this->response->get('noExitingToken', 'default')
        );
    }

    public function testGroup()
    {
        $this->assertEquals('anyGroup', $this->response->group());
    }

    public function testHas()
    {
        $this->assertTrue($this->response->has('token1'));
        $this->assertFalse($this->response->has('noExitingToken'));
    }

    public function testTokens()
    {
        $this->assertEquals(
            [
                'token1',
                'token2'
            ],
            $this->response->tokens()
        );
    }
}
