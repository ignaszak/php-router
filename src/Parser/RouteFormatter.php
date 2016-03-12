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

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Interfaces\IRouteParser;
use Ignaszak\Router\Interfaces\IFormatterLink;
use Ignaszak\Router\Interfaces\IFormatterStart;
use Ignaszak\Router\Route;

class RouteFormatter extends IRouteParser implements
    IFormatterStart,
    IFormatterLink
{

    /**
     *
     * @var Route
     */
    private $route;

    /**
     *
     * @var integer
     */
    private static $counter = 0;

    /**
     *
     * @var string[]
     */
    private $patternArray = [
        'base'  => '',
        '404'   => '.{1,}',
        'dot'   => '\.',
        'digit' => '\d*',
        'alpha' => '[A-Za-z_-]*',
        'alnum' => '[\w-]*'
    ];

    /**
     *
     * @var array
     */
    private $routeArray = [];

    /**
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function format()
    {
        $routeArray = $this->route->getRouteArray();
        foreach ($routeArray as $name => $route) {
            $pattern = $this->addTokens(
                $route['token'] ?? [],
                $route['pattern']
            );
            $pattern = $this->addTokens(
                $this->route->getTokenArray() ?? [],
                $pattern
            );
            $pattern = $this->addTokens($this->patternArray, $pattern, '@');
            $pattern = $this->preparePattern($pattern);

            $this->validRoute($pattern, $name);
            $routeArray[$name]['pattern'] = $pattern;
        }
        $this->routeArray = $routeArray;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IFormatterStart::addPattern($name, $pattern)
     */
    public function addPattern(string $name, string $pattern): IFormatterStart
    {
        $this->patternArray[$name] = $pattern;

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IFormatterStart::addPatterns($patterns)
     */
    public function addPatterns(array $patterns): IFormatterStart
    {
        $this->patternArray = array_merge(
            $this->patternArray,
            $patterns
        );

        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IFormatterLink::getRoute()
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IFormatterLink::getPatternArray()
     */
    public function getPatternArray(): array
    {
        return $this->patternArray;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRouteParser::getRouteArray()
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     *
     * @param array $token
     * @param string $pattern
     * @param string $symbol
     * @return string
     */
    private function addTokens(
        array $token,
        string $pattern,
        string $symbol = ''
    ): string {
        if (empty($token)) {
            return $pattern;
        }

        $p = [];
        $r = [];

        if (empty($symbol)) {
            $open = '{';
            $close = '}';
        } else {
            $open = '';
            $close = '';
        }

        foreach ($token as $key => $value) {
            $p[] = "/{$open}{$symbol}{$key}{$close}/";
            $r[] = $symbol == "@" ? $value : "(?P<{$key}>{$value})";
        }

        return preg_replace($p, $r, $pattern);
    }

    /**
     *
     * @param string $route
     * @throws \RuntimeException
     * @return boolean
     */
    private function validRoute(string $route, string $name = ''): bool
    {
        $m = [];
        if (preg_match_all(
            "/@?:?\\b(?<=:|@)[\\w@,.{|}()*+=?<>\\\\]+/",
            $route,
            $m
        )
            ) {
            throw new \RuntimeException(
                "Detect unadded elements: " .
                preg_replace('/[^\w:@\s,.]+/', '', implode(', ', $m[0])) .
                " in route '{$name}'"
            );
        } else {
            return true;
        }
    }

    /**
     *
     * @param string $pattern
     * @return string
     */
    private function preparePattern(string $pattern): string
    {
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }
}
