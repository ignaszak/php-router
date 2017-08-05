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

namespace Test\Mock;

use \org\bovigo\vfs\vfsStream;
use \org\bovigo\vfs\vfsStreamWrapper;

/**
 * Class MockTest
 * @package Test\Mock
 */
class MockTest
{

    /**
     * @param $object
     * @param string $method
     * @param array $args
     *
     * @return mixed
     */
    public static function callMockMethod(
        $object,
        string $method,
        array $args = []
    )
    {
        $class = new \ReflectionClass(
            is_object($object) ? get_class($object) : $object
        );
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        $object = is_object($object) ? $object : $class;

        return $method->invokeArgs($object, $args);
    }

    /**
     * @param string $file
     * @param int $chmod
     * @param string $content
     *
     * @return string
     */
    public static function mockFile(
        string $file,
        int $chmod = 0644,
        string $content = ""
    ): string
    {
        $root = vfsStream::setup('mock');
        vfsStream::newFile($file,
            $chmod)->at($root)->withContent($content);

        return vfsStream::url("mock/$file");
    }

    /**
     * @param string $dir
     *
     * @return string
     */
    public static function mockDir(string $dir): string
    {
        $root = vfsStream::newDirectory($dir);
        vfsStreamWrapper::setRoot($root);

        return vfsStream::url($dir);
    }

    /**
     * @param $object
     * @param string $property
     * @param null $value
     */
    public static function inject($object, string $property, $value = null)
    {
        $class = $object;
        if (is_object($object)) {
            $class = get_class($object);
        }
        $reflection = new \ReflectionProperty($class, $property);
        $reflection->setAccessible(true);
        $reflection->setValue($object, $value);
    }

    /**
     * @param $class
     * @param string $property
     * @param null $value
     */
    public static function injectStatic(
        $class,
        string $property,
        $value = null
    )
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $reflection = new \ReflectionProperty($class, $property);
        $reflection->setAccessible(true);
        $reflection->setValue(null, $value);
    }
}
