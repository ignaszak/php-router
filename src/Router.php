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

use Ignaszak\Router\Interfaces\IRouter;
use Ignaszak\Router\Interfaces\IResponse;
use Ignaszak\Router\Parser\Parser;
use Ignaszak\Router\Parser\RouteFormatter;
use Ignaszak\Router\Conf\Host;

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

    /**
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->formatter = new RouteFormatter($route);
        $this->parser = new Parser($this->formatter);
        $this->link = Link::instance();
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
    public function run(
        Host $host = null,
        string $query = '',
        string $httpMethod = ''
    ): IResponse {
        $this->link->set($this->formatter, $host);
        $this->formatter->format();
        $this->formatter->sort();
        $response =  new Response(
            $this->parser->run($host, $query, $httpMethod)
        );
        ResponseStatic::$response = $response;
        return $response;
    }
}
