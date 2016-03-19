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

use Ignaszak\Router\Route;

class RouteFormatter
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
        'base'      => '',
        'notfound' => '.+',
        'dot'       => '\.',
        'digit'     => '(\d+)',
        'alpha'     => '([A-Za-z_-]+)',
        'alnum'     => '([\w-]+)'
    ];

    /**
     *
     * @var array
     */
    private $routeArray = [];

    /**
     * Stores added tokens name as key and token pattern as value
     *
     * @var string[]
     */
    private $tokenArray = [];

    /**
     *
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Parser\RouteFormatter
     */
    public function addToken(string $name, string $pattern): RouteFormatter
    {
        $this->tokenArray[$name] = $pattern;

        return $this;
    }

    /**
     *
     * @param string[] $tokens
     * @return \Ignaszak\Router\Parser\RouteFormatter
     */
    public function addTokens(array $tokens): RouteFormatter
    {
        $this->tokenArray = array_merge(
            $this->tokenArray,
            $tokens
        );

        return $this;
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Parser\RouteFormatter
     */
    public function addPattern(string $name, string $pattern): RouteFormatter
    {
        $this->patternArray[$name] = $pattern;

        return $this;
    }

    /**
     *
     * @param string[] $patterns
     * @return \Ignaszak\Router\Parser\RouteFormatter
     */
    public function addPatterns(array $patterns): RouteFormatter
    {
        $this->patternArray = array_merge(
            $this->patternArray,
            $patterns
        );

        return $this;
    }

    /**
     *
     * @return \Ignaszak\Router\Route
     */
    public function getRoute(): Route
    {
        return $this->route;
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
     * @return string[]
     */
    public function getTokenArray(): array
    {
        return $this->tokenArray;
    }

    /**
     *
     * @return string[]
     */
    public function getPatternArray(): array
    {
        return $this->patternArray;
    }

    public function sort()
    {
        uasort(
            $this->routeArray,
            function ($a, $b) {
                return strlen($b['pattern']) <=> strlen($a['pattern']);
            }
        );
    }

    public function format()
    {
        $routeArray = $this->route->getRouteArray();
        foreach ($routeArray as $name => $route) {
            $pattern = $this->addTokensToRoute(
                $route['token'] ?? [],
                $route['pattern']
            );
            $pattern = $this->addTokensToRoute(
                $this->tokenArray ?? [],
                $pattern
            );
            $pattern = $this->addTokensToRoute(
                $this->patternArray,
                $pattern,
                '@'
            );
            $pattern = $this->preparePattern($pattern);

            $this->validRoute($pattern, (string)$name);
            $routeArray[$name]['pattern'] = $pattern;
        }
        $this->routeArray = $routeArray;
    }

    /**
     *
     * @param array $token
     * @param string $pattern
     * @param string $symbol
     * @return string
     */
    private function addTokensToRoute(
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
        )) {
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
