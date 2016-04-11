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
use Ignaszak\Router\Collection\IRoute;

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
     * @var IRoute
     */
    private $route = null;

    /**
     *
     * @var Parser
     */
    private $parser = null;

    /**
     *
     * @var Link
     */
    private $link = null;

    /**
     *
     * @param Route $route
     */
    public function __construct(IRoute $route)
    {
        if (empty($route->getChecksum())) {
            $this->route = $route;
        } else {
            $this->route = new RouteFormatter($route);
        }
        $this->parser = new Parser($this->route);
        $this->link = Link::instance();
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
        $formattedRouteArray = $this->route->getRouteArray();
        $this->link->set($formattedRouteArray, $host);
        $response =  new Response(
            $this->parser->run(
                $host,
                $query,
                $httpMethod
            )
        );
        return $response;
    }
}
