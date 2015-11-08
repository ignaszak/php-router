<?php

namespace Ignaszak\Router;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Conf.php
 *
 */
class Conf
{

    /**
     * @var Conf
     */
    private static $_conf;

    /**
     * @var string
     */
    private $baseURL;

    /**
     * @var string
     */
    private $defaultRoute;

    /**
     * @return Conf
     */
    public static function instance()
    {
        if (empty(self::$_conf))
            self::$_conf = new Conf;

        return self::$_conf;
    }

    /**
     * @param string $property
     * @param string $value
     */
    public function setProperty($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * @param string $property
     * @return string
     */
    public static function get($property)
    {
        if (property_exists(self::$_conf, $property)) {
            return self::$_conf->$property;
        }
    }

    /**
     * @return string
     */
    public static function getQueryString()
    {
        $host = new Host(self::$_conf->baseURL);
        return $host->getQueryString();
    }

}
