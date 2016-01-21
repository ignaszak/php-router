<?php

namespace Test;

use Ignaszak\Router\Conf;

class ConfTest extends \PHPUnit_Framework_TestCase
{

    private $_conf;

    public function __construct()
    {
        $this->_conf = Conf::instance();
        $this->_conf->setProperty('baseURL', 'http://192.168.1.2/~tomek/Eclipse/PHP/Router/');
        @$_SERVER['HTTPS'] == 'off';
        @$_SERVER['SERVER_NAME'] = '192.168.1.2';
        @$_SERVER['REQUEST_URI'] = '/~tomek/Eclipse/PHP/Router/router';
    }

    public function testGetQueryString()
    {
        $this->assertEquals('router', Conf::getQueryString());
    }
}
