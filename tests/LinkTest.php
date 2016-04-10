<?php
namespace Test;

use Ignaszak\Router\Link;
use Test\Mock\MockTest;

class LinkTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Link
     */
    private $link;

    public function setUp()
    {
        MockTest::injectStatic('Ignaszak\Router\Link', 'link');
        $this->link = Link::instance();
    }

    public function testInstance()
    {
        $this->assertInstanceOf('Ignaszak\Router\Link', Link::instance());
    }

    public function testSet()
    {
        $this->link->set(['formattedRouteArray']);
        $this->assertEquals(
            ['formattedRouteArray'],
            \PHPUnit_Framework_Assert::readAttribute(
                $this->link,
                'formattedRouteArray'
            )
        );
    }

    public function testGetLink()
    {
        $formattedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ]
            ]
        ];
        $this->link->set($formattedRouteArray);
        $this->assertEquals(
            '/test/anyalias.html',
            $this->link->getLink('name', [
                'alias' => 'anyalias',
                'format' => 'html'
            ])
        );
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testGetLinkWithInvalidValue()
    {
        $formattedRouteArray = [
            'name' => [
                'route' => '/test/{alias}.{format}',
                'path' => '/^\/test\/(?P<alias>(\w+))\.(?P<format>(html|xml|json))$/',
                'tokens' => [
                    'alias' => '(\w+)',
                    'format' => '(html|xml|json)'
                ]
            ]
        ];
        $this->link->set($formattedRouteArray);
        $this->link->getLink('name', [
                'alias' => 'ANYALIAS',
                'format' => 'doc'
        ]);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testInvalidRouteName()
    {
        $this->link->set([]);
        $this->link->getLink('name', []);
    }

    /**
     * @expectedException \Ignaszak\Router\RouterException
     */
    public function testInvalidLink()
    {
        MockTest::callMockMethod($this->link, 'validLink', [
            '/anyLink/with/unreplaced/(?P<token>([a-z]+))/', 'routName'
        ]);
    }

    public function testValidLink()
    {
        $this->assertTrue(
            MockTest::callMockMethod($this->link, 'validLink', [
                '/anyLink/with/replaced/token/', 'routName'
            ])
        );
    }
}
