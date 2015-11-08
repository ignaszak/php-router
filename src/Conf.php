<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Ignaszak\Router;

/**
 * Store configuration settings
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Conf.php
 *
 */
class Conf
{

    /**
     * Stores instance of Conf class
     * 
     * @var Conf
     */
    private static $_conf;

    /**
     * Stores defined base url
     * 
     * @var string
     */
    private $baseURL;

    /**
     * Default route name
     * 
     * @var string
     */
    private $defaultRoute;

    /**
     * Singelton design pattern
     * 
     * @return Conf
     */
    public static function instance()
    {
        if (empty(self::$_conf))
            self::$_conf = new Conf;

        return self::$_conf;
    }

    /**
     * Sets property value
     * 
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
     * Returns property
     * 
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
     * Returns current query string from $_SERVER['REQUEST_URI']
     * 
     * @return string
     */
    public static function getQueryString()
    {
        $host = new Host(self::$_conf->baseURL);
        return $host->getQueryString();
    }

}
