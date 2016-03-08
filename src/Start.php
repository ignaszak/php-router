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
declare(strict_types=1);

namespace Ignaszak\Router;

use Ignaszak\Router\Parser\Parser;
use Ignaszak\Router\Conf\Conf;

/**
 * Initializes router
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Start.php
 *
 */
class Start implements Interfaces\IStart
{

    /**
     *
     * @var Start
     */
    private static $start;

    /**
     *
     * @var Conf
     */
    private $conf;

    /**
     *
     * @var Route
     */
    private $route;

    /**
     *
     * @var RouteParser
     */
    private $parser;

    private function __construct()
    {
        $this->conf = Conf::instance();
        $this->route = new Route();
        $this->parser = new Parser($this->route);
    }

    /**
     *
     * @return Start
     */
    public static function instance()
    {
        if (empty(self::$start)) {
            self::$start = new self();
        }

        return self::$start;
    }

    /**
     * Sets Conf property value
     *
     * @param string $property
     * @param string $value
     */
    public function __set(string $property, string $value)
    {
        $this->conf->setProperty($property, $value);
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     */
    public function add(string $name, string $pattern, string $controller = '')
    {
        $this->route->add($name, $pattern, $controller);
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     */
    public function addToken(string $name, string $pattern)
    {
        $this->route->addToken($name, $pattern);
    }

    public function run()
    {
        $this->route->add(Conf::get('defaultRoute'), '(.*)');
        //$this->checkForDuplicates();
        $this->route->sort();
        $this->parser->run();
    }
}
