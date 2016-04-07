<?php
namespace Test;

use Ignaszak\Router\Yaml;
use Test\Mock\MockTest;

class YamlTest extends \PHPUnit_Framework_TestCase
{

    private $yaml;

    public function setUp()
    {
        $this->yaml = new Yaml();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Symfony\Component\Yaml\Parser',
            \PHPUnit_Framework_Assert::readAttribute($this->yaml, 'parser')
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddNoExistingFile()
    {
        $this->yaml->add('noexistingfile.yml');
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddNoReadableFile()
    {
        $this->yaml->add(MockTest::mockFile('routesdds.yml', 4755));
    }

    public function testAdd()
    {
        $route = <<<EOT
test:
    path: '/test/{controller}/{action}'
    group: ''
    method: ''
    controller: '\Namespace\{controller}::{action}'
    tokens:
        controller: '([a-zA-Z]+)'
        action: '([a-zA-Z]+)'
EOT;
        $yaml = MockTest::mockFile('route.yml', 0644, $route);
        $this->yaml->add($yaml);
        $this->assertEquals(
            [
                'test' => [
                    'path' => '/test/{controller}/{action}',
                    'group' => '',
                    'method' => '',
                    'controller' => '\Namespace\{controller}::{action}',
                    'tokens' => [
                        'controller' => '([a-zA-Z]+)',
                        'action' => '([a-zA-Z]+)'
                    ]
                ]
            ],
            $this->yaml->getRouteArray()
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddWithSameKeys()
    {
        $this->yaml->add(MockTest::mockFile('route.yml', 0644, <<<EOT
test:
    path: '/test/{controller}/{action}'
EOT
        ));
        $this->yaml->add(MockTest::mockFile('route.yml', 0644, <<<EOT
test:
    path: '/test/{controller}/{action}'
EOT
        ));
    }
}
