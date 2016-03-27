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
use Ignaszak\Router\RouterException;

class RouteFormatter
{

    /**
     *
     * @var Route
     */
    private $route;

    /**
     *
     * @var string[]
     */
    private $patternArray = [
        '@base'      => '',
        '@notfound' => '.+',
        '@dot'       => '\.',
        '@digit'     => '(\d+)',
        '@alpha'     => '([A-Za-z_-]+)',
        '@alnum'     => '([\w-]+)'
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
        $this->patternArray["@{$name}"] = $pattern;

        return $this;
    }

    /**
     *
     * @param string[] $patterns
     * @return \Ignaszak\Router\Parser\RouteFormatter
     */
    public function addPatterns(array $patterns): RouteFormatter
    {
        foreach ($patterns as $name => $pattern) {
            $this->addPattern($name, $pattern);
        }

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
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
        foreach ($this->route->getRouteArray() as $name => $route) {
            $patternKey = array_keys($this->patternArray);
            $tokens = [];
            $subpatterns = [];
            $search = [];
            $m = [];

            if (preg_match_all('/{(\w+)}/', $route['pattern'], $m)) {
                foreach ($m[1] as $token) {
                    $search[] = "{{$token}}";
                    $tokens[$token] = str_replace(
                        $patternKey,
                        $this->patternArray,
                        $route['token'][$token] ?? $this->tokenArray[$token]
                    );
                    $subpatterns[$token] = "(?P<{$token}>{$tokens[$token]})";
                }
            }

            $route['token'] = $tokens;
            $route['route'] = $route['pattern'];
            $route['pattern'] = str_replace(
                $search,
                $subpatterns,
                $route['pattern']
            );
            $route['pattern'] = $this->preparePattern(str_replace(
                $patternKey,
                $this->patternArray,
                $route['pattern']
            ));
            $this->validPattern($route['pattern'], (string)$name);
            $this->routeArray[$name] = $route;
        }
    }

    /**
     *
     * @param string $route
     * @throws RouterException
     * @return boolean
     */
    private function validPattern(string $route, string $name = ''): bool
    {
        $m = [];
        if (preg_match_all(
            "/@?:?\\b(?<=:|@)[\\w@,.{|}()*+=?<>\\\\]+/",
            $route,
            $m
        )) {
            throw new RouterException(
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
