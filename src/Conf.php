<?php

namespace Ignaszak\Router;

class Conf
{

    private static $_conf;
    private $baseURL;
    private $defaultRoute;

    public static function instance()
    {
        if (empty(self::$_conf))
            self::$_conf = new Conf;

        return self::$_conf;
    }

    public function setProperty($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public static function get($property)
    {
        if (property_exists(self::$_conf, $property)) {
            return self::$_conf->$property;
        }
    }

    public static function getQueryString()
    {
        $host = new Host(self::$_conf->baseURL);
        return $host->getQueryString();
    }

}
