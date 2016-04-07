<?php
namespace Test;

use Ignaszak\Router\Yaml;
use Test\Mock\MockTest;
use Symfony\Component\Yaml\Parser;

class YamlTest extends \PHPUnit_Framework_TestCase
{

    private $yaml;

    public function setUp()
    {
        $this->yaml = new Yaml();
    }

    public function testAdd()
    {
        $route = "";
        $yaml = MockTest::mockFile('route.yml', 0644, $route);
        $this->yaml->add($yaml);
        $yaml = new Parser();
        $value = $yaml->parse(file_get_contents(__DIR__ . '/file.yml'));
        print_r($value);
    }
}
