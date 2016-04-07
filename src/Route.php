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

use Ignaszak\Router\Interfaces\IRouteAdd;
use Ignaszak\Router\Interfaces\IRouteStart;
use Ignaszak\Router\Interfaces\IRoute;

class Route implements IRouteStart, IRouteAdd, IRoute
{

    /**
     * Stores added routes
     *
     * @var array
     */
    private $routeArray = [];

    /**
     *
     * @var string
     */
    private $lastName = '';

    /**
     *
     * @var string
     */
    private $group = '';

    private function __construct()
    {
    }

    /**
     *
     * @return IRouteStart
     */
    public static function start(): IRouteStart
    {
        return new Route();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRoute::getRouteArray()
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::add($name, $pattern)
     */
    public function add(
        string $name = null,
        string $pattern,
        string $method = ''
    ): IRouteAdd {
        if (is_null($name)) {
            $this->routeArray[] = ['path' => $pattern];
            // Last array key
            $name = key(array_slice($this->routeArray, -1, 1, true));
        } else {
            if (array_key_exists($name, $this->routeArray)) {
                throw new RouterException(
                    "Route name '{$name}' alredy exists"
                );
            }
            $this->routeArray[$name] = ['path' => $pattern];
        }
        $this->routeArray[$name]['group'] = $this->group;
        $this->routeArray[$name]['method'] = $method;
        $this->lastName = $name;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::get($name, $pattern)
     */
    public function get(string $name = null, string $pattern): IRouteAdd
    {
        $this->add($name, $pattern, 'GET');

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::post($name, $pattern)
     */
    public function post(string $name = null, string $pattern): IRouteAdd
    {
        $this->add($name, $pattern, 'POST');

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteAdd::controller($controller)
     */
    public function controller(string $controller): IRouteAdd
    {
        $this->routeArray[$this->lastName]['controller'] = $controller;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteAdd::token($token)
     */
    public function token(string $name, string $pattern): IRouteAdd
    {
        $this->routeArray[$this->lastName]['tokens'][$name] = $pattern;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteAdd::tokens($tokens)
     */
    public function tokens(array $tokens): IRouteAdd
    {
        $this->routeArray[$this->lastName]['tokens'] = array_merge(
            $this->routeArray[$this->lastName]['tokens'] ?? [],
            $tokens
        );

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteAdd::attach($closure)
     */
    public function attach(\Closure $closure, bool $call = true): IRouteAdd
    {
        $this->routeArray[$this->lastName]['callAttachment'] = $call;
        $this->routeArray[$this->lastName]['attachment'] = $closure;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::group($name)
     */
    public function group(string $name = ''): IRouteStart
    {
        $this->group = $name;

        return $this;
    }
}
