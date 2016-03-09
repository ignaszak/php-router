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

use Ignaszak\Router\Interfaces\IRouteParser;

/**
 * Class provides methods for users to get matched routes
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Client implements Interfaces\IClient
{

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getRoute($route)
     */
    public static function getRoute(string $route): string
    {
        return IRouteParser::$request[$route] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getRoutes()
     */
    public static function getRoutes(): array
    {
        return IRouteParser::$request;
    }
}
