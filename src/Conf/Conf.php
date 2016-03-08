<?php
/**
 *
 * PHP Version 7.0
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
declare(strict_types=1);

namespace Ignaszak\Router\Conf;

/**
 * Stores configuration settings
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Conf
{

    /**
     * Stores instance of Conf class
     *
     * @var Conf
     */
    private static $conf;

    /**
     *
     * @var Host
     */
    private $host;

    /**
     * Stores defined base url
     *
     * @var string
     */
    private $baseURI = '';

    /**
     * Default route name
     *
     * @var string
     */
    private $defaultRoute = '';

    private function __construct()
    {
        $this->host = new Host();
    }

    /**
     * Singelton design pattern
     *
     * @return Conf
     */
    public static function instance()
    {
        if (empty(self::$conf)) {
            self::$conf = new Conf;
        }

        return self::$conf;
    }

    /**
     * Sets property value
     *
     * @param string $property
     * @param string $value
     */
    public function setProperty(string $property, string $value)
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
    public static function get(string $property): string
    {
        if (property_exists(self::$conf, $property)) {
            return self::$conf->$property;
        }
    }

    /**
     * Returns current query string from $_SERVER['REQUEST_URI']
     *
     * @return string
     */
    public static function getQueryString(): string
    {
        self::$conf->host->validBaseURI(self::$conf->baseURI);
        return self::$conf->host->getQueryString() ?? '';
    }
}
