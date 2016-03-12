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
     * @return array
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     * @return array
     */
    public function getTokenArray(): array
    {
        return $this->tokenArray;
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     */
    public function add(string $name, string $pattern): IRouteAdd
    {
        $this->lastName = $name;
        $this->routeArray[$name] = [
            'pattern' => $pattern
        ];

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
     * Sorts route array
     */
    public function sort()
    {
        uasort(
            $this->routeArray,
            function ($a, $b) {
                return strlen($b['pattern']) <=> strlen($a['pattern']);
            }
        );
    }
}
