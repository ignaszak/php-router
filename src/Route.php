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

class Route implements IRouteStart, IRouteAdd
{

    /**
     * Stores added routes
     *
     * @var array
     */
    private $routeArray = [];

    /**
     * Stores added tokens name as key and token pattern as value
     *
     * @var array
     */
    private $tokenArray = [];

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
     * @return array
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     *
     * @return array
     */
    public function getTokenArray(): array
    {
        return $this->tokenArray;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::add($name, $pattern)
     */
    public function add(string $name = null, string $pattern): IRouteAdd
    {
        if (is_null($name)) {
            $this->routeArray[] = ['pattern' => $pattern];
            // Last array key
            $name = key(array_slice($this->routeArray, -1, 1, true));
        } else {
            if (array_key_exists($name, $this->routeArray)) {
                throw new \RuntimeException(
                    "Route name '{$name}' alredy exists"
                );
            }
            $this->routeArray[$name] = ['pattern' => $pattern];
        }
        $this->routeArray[$name]['group'] = $this->group;
        $this->lastName = $name;

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
        $this->routeArray[$this->lastName]['token'][$name] = $pattern;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteAdd::tokens($tokens)
     */
    public function tokens(array $tokens): IRouteAdd
    {
        $this->routeArray[$this->lastName]['token'] = array_merge(
            $this->routeArray[$this->lastName]['token'] ?? [],
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
     * @see \Ignaszak\Router\Interfaces\IRouteStart::addToken($name, $pattern)
     */
    public function addToken(string $name, string $pattern): IRouteStart
    {
        $this->tokenArray[$name] = $pattern;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteStart::addTokens($tokens)
     */
    public function addTokens(array $tokens): IRouteStart
    {
        $this->tokenArray = array_merge(
            $this->tokenArray,
            $tokens
        );

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
