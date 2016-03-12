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

namespace Ignaszak\Router;

use Ignaszak\Router\Conf\Conf;
use Ignaszak\Router\Interfaces\IFormatterStart;
use Ignaszak\Router\Interfaces\IRouteAdd;
use Ignaszak\Router\Interfaces\IRouteStart;
use Ignaszak\Router\Interfaces\IStart;
use Ignaszak\Router\Parser\Parser;
use Ignaszak\Router\Parser\RouteFormatter;

/**
 * Initializes router
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
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
     * @var RouteFormatter
     */
    private $formatter;

    /**
     *
     * @var Parser
     */
    private $parser;

    /**
     *
     * @var Link
     */
    private $link;

    private function __construct()
    {
        $this->conf = Conf::instance();
        $this->route = new Route();
        $this->formatter = new RouteFormatter($this->route);
        $this->parser = new Parser($this->formatter);
        $this->link = Link::instance();
        $this->link->set($this->formatter);
    }

    /**
     *
     * {@inheritDoc}
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
        if ($property == 'baseURI') {
            $this->conf->baseURI = $value;
        } else {
            throw new \RuntimeException('Invalid property');
        }
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
     * @see \Ignaszak\Router\Interfaces\IStart::addTokens($tokens)
     */
    public function addTokens(array $tokens): IRouteStart
    {
        return $this->route->addTokens($tokens);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::addPattern($name, $pattern)
     */
    public function addPattern(string $name, string $pattern): IFormatterStart
    {
        return $this->formatter->addPattern($name, $pattern);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::addPatterns($patterns)
     */
    public function addPatterns(array $patterns): IFormatterStart
    {
        return $this->formatter->addPatterns($patterns);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IStart::run()
     */
    public function run()
    {
        $this->route->sort();
        $this->formatter->format();
        $this->parser->run();
    }
}
