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
use Ignaszak\Router\Interfaces\IRouteAdd;
use Ignaszak\Router\Interfaces\IRouteStart;
use Ignaszak\Router\Interfaces\IStart;

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
     *  {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::instance()
     */
    public static function instance(): IStart
    {
        if (empty(self::$start)) {
            self::$start = new self();
        }

        return self::$start;
    }

    /**
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
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::add($name, $pattern)
     */
    public function add(string $name, string $pattern): IRouteAdd
    {
        return $this->route->add($name, $pattern);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::addToken($name, $pattern)
     */
    public function addToken(string $name, string $pattern): IRouteStart
    {
        return $this->route->addToken($name, $pattern);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::run()
     */
    public function run()
    {
        //$this->route->add(Conf::get('defaultRoute'), '(.*)');
        //$this->checkForDuplicates();
        $this->route->sort();
        $this->parser->run();
    }
}
