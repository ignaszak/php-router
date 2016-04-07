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

use Ignaszak\Router\RouterException;
use Ignaszak\Router\Interfaces\IRoute;

class RouteFormatter
{

    /**
     *
     * @var IRoute
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
    public function __construct(IRoute $route)
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
                return strlen($b['path']) <=> strlen($a['path']);
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

            if (preg_match_all('/{(\w+)}/', $route['path'], $m)) {
                foreach ($m[1] as $token) {
                    $search[] = "{{$token}}";
                    $tokens[$token] = str_replace(
                        $patternKey,
                        $this->patternArray,
                        $route['tokens'][$token] ?? $this->tokenArray[$token]
                    );
                    $subpatterns[$token] = "(?P<{$token}>{$tokens[$token]})";
                }
            }

            $route['tokens'] = $tokens;
            $route['route'] = $route['path'];
            $route['path'] = str_replace(
                $search,
                $subpatterns,
                $route['path']
            );
            $route['path'] = $this->preparePattern(str_replace(
                $patternKey,
                $this->patternArray,
                $route['path']
            ));
            $this->validPattern($route['path'], (string)$name);
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
