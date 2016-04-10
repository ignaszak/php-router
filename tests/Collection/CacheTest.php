<?php
namespace Test\Collection;

use Ignaszak\Router\Collection\Cache;
use Test\Mock\MockTest;

class CacheTest extends \PHPUnit_Framework_TestCase
{

    private $cache;

    public function setUp()
    {
        $this->cache = new Cache($this->mockIRoute());
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(
            'Ignaszak\Router\Collection\IRoute',
            \PHPUnit_Framework_Assert::readAttribute($this->cache, 'route')
        );
        $this->assertInstanceOf(
            'Ignaszak\Router\Parser\RouteFormatter',
            \PHPUnit_Framework_Assert::readAttribute(
                $this->cache,
                'formatter'
            )
        );
    }

    public function testSetTmpDir()
    {
        $this->cache->tmpDir = 'anyDir';
        $this->assertEquals(
            'anyDir',
            \PHPUnit_Framework_Assert::readAttribute($this->cache, 'tmpDir')
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testSetToInvalidProperty()
    {
        $this->cache->invalidProperty = 'anyDir';
    }

    public function testCheckNoExistingTmpRoute()
    {
        $this->assertEmpty(
            MockTest::callMockMethod(
                $this->cache,
                'loadTmpRoute',
                ['noExistingFile']
            )
        );
    }

    public function testCheckNoReadableTmpRoute()
    {
        $file = MockTest::mockFile('file.php', 0222);
        $this->assertEmpty(
            MockTest::callMockMethod(
                $this->cache,
                'loadTmpRoute',
                [$file]
            )
        );
    }

    public function testCheckTmpRoute()
    {
        $content = <<<EOT
<?php

return [
    'routes' => [
        'name' => [
            'path' => 'anyPattern'
        ]
    ],
    'checksum' => 'anyChecksum'
];

EOT;
        $file = MockTest::mockFile('file.php', 0644, $content);
        $this->cache = new Cache($this->mockIRoute('anyChecksum'));
        $this->assertEquals(
            [
                'name' => [
                    'path' => 'anyPattern'
                ]
            ],
            MockTest::callMockMethod(
                $this->cache,
                'loadTmpRoute',
                [$file]
            )
        );
    }

    public function testInccorectChecksum()
    {
        $content = <<<EOT
<?php

return [
    'checksum' => 'anyChecksum'
];

EOT;
        $file = MockTest::mockFile('file.php', 0644, $content);
        $this->cache = new Cache($this->mockIRoute('anotherChecksum'));
        $this->assertEmpty(
            MockTest::callMockMethod(
                $this->cache,
                'loadTmpRoute',
                [$file]
            )
        );
    }

    public function testSaveTmpRoute()
    {
        $this->cache = new Cache(
            $this->mockIRoute('anyChecksum')
        );
        $file = MockTest::mockFile('file.php', 0644);
        MockTest::callMockMethod(
            $this->cache,
            'saveTmpRoute',
            [$file, ['anyRouteArray']]
        );
        $this->assertEquals(
            [
                'routes' => ['anyRouteArray'],
                'checksum' => 'anyChecksum'
            ],
            include $file
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testUnableToSaveTmpRoute()
    {
        $file = MockTest::mockFile('file.php', 0444);
        MockTest::callMockMethod($this->cache, 'saveTmpRoute', [$file, []]);
    }

    public function testGetRouteArray()
    {
        // Read from formatter
        $this->cache = new Cache($this->mockIRoute('anyChecksum'));
        MockTest::inject(
            $this->cache,
            'formatter',
            $this->mockRouteFormatter(['anyRouteArray'])
        );
        $tmpDir = MockTest::mockDir('anyDir');
        MockTest::inject($this->cache, 'tmpDir', $tmpDir);
        $this->assertEquals(
            ['anyRouteArray'],
            $this->cache->getRouteArray()
        );

        // Read from cache
        MockTest::inject(
            $this->cache,
            'formatter',
            $this->mockRouteFormatter(['shouldReadFromCache'])
        );
        $this->assertEquals(
            ['anyRouteArray'],
            $this->cache->getRouteArray()
        );
    }

    private function mockIRoute(string $checksum = '')
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Collection\IRoute')
            ->getMock();
        $stub->method('getChecksum')->willReturn($checksum);
        return $stub;
    }

    private function mockRouteFormatter(array $route = [])
    {
        $stub = $this->getMockBuilder('Ignaszak\Router\Parser\RouteFormatter')
            ->disableOriginalConstructor()->getMock();
        $stub->method('getRouteArray')->willReturn($route);
        return $stub;
    }
}
