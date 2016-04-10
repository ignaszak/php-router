<?php
namespace Test\Collection;

use Test\Mock\MockTest;
use Ignaszak\Router\Collection\Yaml;

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

    public function testAdd()
    {
        $this->yaml->add(MockTest::mockFile('routesFile.yml'));
        $this->assertEquals(
            ['vfs://mock/routesFile.yml'],
            \PHPUnit_Framework_Assert::readAttribute($this->yaml, 'fileArray')
        );
        $this->assertEquals(
            time(),
            \PHPUnit_Framework_Assert::readAttribute($this->yaml, 'fileMTime')
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddNotExistingFile()
    {
        $this->yaml->add('noexistingfile.yml');
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testAddNotReadableFile()
    {
        $this->yaml->add(MockTest::mockFile('routesdds.yml', 4755));
    }

    public function testGetRouteArray()
    {
        $route = <<<EOT
routes:
    test:
        path: '/test/{controller}/{action}'
        group: ''
        method: ''
        controller: '\Namespace\{controller}::{action}'
        tokens:
            controller: '([a-zA-Z]+)'
tokens:
    action: '([a-zA-Z]+)'
patterns:
    patternName: '(.+)'
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
                        'controller' => '([a-zA-Z]+)'
                    ]
                ]
            ],
            $this->yaml->getRouteArray()['routes']
        );
        $this->assertEquals(
            [
                'action' => '([a-zA-Z]+)'
            ],
            $this->yaml->getRouteArray()['tokens']
        );
        $this->assertEquals(
            [
                'patternName' => '(.+)'
            ],
            $this->yaml->getRouteArray()['patterns']
        );
    }

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
        $this->yaml->getRouteArray();
    }

    public function testGetChecksum()
    {
        $this->yaml->add(MockTest::mockFile('route.yml'));
        $this->assertEquals(
            md5(time()),
            $this->yaml->getChecksum()
        );
    }
}
