<?php
namespace Test\Mock;

use org\bovigo\vfs\vfsStream as Vsf;

class MockTest
{

    /**
     *
     * @param object|string $object
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public static function callMockMethod(
        $object,
        string $method,
        array $args = []
    ) {
        $class = new \ReflectionClass(
            is_object($object) ? get_class($object) : $object
        );
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        $object = is_object($object) ? $object : $class;
        return $method->invokeArgs($object, $args);
    }

    /**
     *
     * @param string $file
     * @param string $content
     */
    public static function mockFile(
        string $file,
        int $chmod = 0644,
        string $content = ""
    ): string {
        $root = Vsf::setup('mock');
        Vsf::newFile($file, $chmod)->at($root)->withContent($content);
        return Vsf::url("mock/$file");
    }

    /**
     *
     * @param object $class
     * @param string $property
     * @param mixed $value
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
     *
     * @param string|object $class
     * @param string $property
     * @param mixed $value
     */
    public static function injectStatic($class, string $property, $value = null)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }
        $reflection = new \ReflectionProperty($class, $property);
        $reflection->setAccessible(true);
        $reflection->setValue(null, $value);
    }
}
