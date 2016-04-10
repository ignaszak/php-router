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
use Ignaszak\Router\Collection\IRoute;

class RouteFormatter implements IRoute
{

    /**
     *
     * @var IRoute
     */
    private $route = null;

    /**
     *
     * @var string[]
     */
    private $patternArray = [
        '@base'     => '',
        '@notfound' => '.+',
        '@dot'      => '\.',
        '@digit'    => '(\d+)',
        '@alpha'    => '([A-Za-z_-]+)',
        '@alnum'    => '([\w-]+)'
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
    public function __construct(IRoute $route)
    {
        $this->route = $route;
    }

    /**
     *
     * @return array
     */
    public function getRouteArray(): array
    {
        $this->format();
        $this->sort();
        return $this->routeArray;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Collection\IRoute::getChecksum()
     */
    public function getChecksum(): string
    {
        return '';
    }

    private function sort()
    {
        uasort(
            $this->routeArray,
            function ($a, $b) {
                return strlen($b['path']) <=> strlen($a['path']);
            }
        );
    }

    private function format()
    {
        $patternArray = $this->getPatterns();
        $tokenArray = $this->route->getRouteArray()['tokens'] ?? [];
        $routeArray = $this->route->getRouteArray()['routes'] ?? [];

        foreach ($routeArray as $name => $route) {
            $patternKey = array_keys($patternArray);
            $tokens = [];
            $subpatterns = [];
            $search = [];
            $m = [];

            if (preg_match_all('/{(\w+)}/', $route['path'], $m)) {
                foreach ($m[1] as $token) {
                    $search[] = "{{$token}}";
                    $tokens[$token] = str_replace(
                        $patternKey,
                        $patternArray,
                        $route['tokens'][$token] ?? $tokenArray[$token]
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
                $patternArray,
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

    /**
     *
     * @return string[]
     */
    private function getPatterns(): array
    {
        $result = [];
        $patternArray = $this->route->getRouteArray()['patterns'] ?? [];
        foreach ($patternArray as $key => $value) {
            $result["@{$key}"] = $value;
        }
        return array_merge($this->patternArray, $result);
    }
}
