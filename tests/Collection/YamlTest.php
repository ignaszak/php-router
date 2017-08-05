<?php
/**
 *
 * PHP Version 7.0
 *
 * @copyright 2016 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 *
 */
declare(strict_types=1);

namespace Test\Collection;

use Test\Mock\MockTest;
use Ignaszak\Router\Collection\Yaml;

/**
 * Class YamlTest
 * @package Test\Collection
 */
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
        MockTest::inject($this->yaml, 'fileArray', [
            MockTest::mockFile('file.yml')
        ]);

        $yamlParser = $this->getMockBuilder('Symfony\Component\Yaml\Parser')
            ->disableOriginalConstructor()->getMock();
        $yamlParser->expects($this->once())->method('parse')->willReturn([]);
        MockTest::inject($this->yaml, 'parser', $yamlParser);

        $stub = $this->getMockBuilder('Ignaszak\Router\Matcher\Converter')
            ->disableOriginalConstructor()->getMock();
        $stub->expects($this->once())->method('convert')->willReturn([]);
        MockTest::inject($this->yaml, 'converter', $stub);

        $this->yaml->getRouteArray();
    }

    public function testGetChecksum()
    {
        $this->yaml->add(MockTest::mockFile('route.yml'));
        $this->assertEquals(
            md5((string) time()),
            $this->yaml->getChecksum()
        );
    }
}
