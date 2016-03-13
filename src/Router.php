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
use Ignaszak\Router\Interfaces\IRouter;
use Ignaszak\Router\Parser\Parser;
use Ignaszak\Router\Parser\RouteFormatter;

/**
 * Initializes router
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Router implements Interfaces\IRouter
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

    public function __construct(Route $route)
    {
        $this->conf = Conf::instance();
        $this->formatter = new RouteFormatter($route);
        $this->parser = new Parser($this->formatter);
        $this->link = Link::instance();
        $this->link->set($this->formatter);
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
     * @see \Ignaszak\Router\Interfaces\IRouter::addToken($name, $pattern)
     */
    public function addToken(string $name, string $pattern): IRouter
    {
        $this->formatter->addToken($name, $pattern);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouter::addTokens($patterns)
     */
    public function addTokens(array $patterns): IRouter
    {
        $this->formatter->addTokens($patterns);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouter::addPattern($name, $pattern)
     */
    public function addPattern(string $name, string $pattern): IRouter
    {
        $this->formatter->addPattern($name, $pattern);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouter::addPatterns($patterns)
     */
    public function addPatterns(array $patterns): IRouter
    {
        $this->formatter->addPatterns($patterns);

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouter::run()
     */
    public function run()
    {
        $this->formatter->format();
        $this->formatter->sort();
        $this->parser->run();
    }
}
