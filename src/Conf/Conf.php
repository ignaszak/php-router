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
     *
     * @var string
     */
    public $baseURI = '';

    /**
     *
     * @var Conf
     */
    private static $conf;

    /**
     *
     * @var Host
     */
    private $host;

    private function __construct()
    {
        $this->host = new Host();
    }

    /**
     *
     * @return Conf
     */
    public static function instance(): Conf
    {
        if (empty(self::$conf)) {
            self::$conf = new Conf;
        }

        return self::$conf;
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
