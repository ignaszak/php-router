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
        return IRouteParser::$request['routes'][$route] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getRoutes()
     */
    public static function getRoutes(): array
    {
        return IRouteParser::$request['routes'] ?? [];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getName()
     */
    public static function getName(): string
    {
        return IRouteParser::$request['name'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getController()
     */
    public static function getController(): string
    {
        return IRouteParser::$request['controller'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getGroup()
     */
    public static function getGroup(): string
    {
        return IRouteParser::$request['group'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getAttachment()
     */
    public static function getAttachment(): \Closure
    {
        return IRouteParser::$request['attachment'] instanceof \Closure ?
        IRouteParser::$request['attachment'] : function () {
        };
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IClient::getLink()
     */
    public static function getLink(string $name, array $replacement): string
    {
        return Link::instance()->getLink($name, $replacement);
    }
}
