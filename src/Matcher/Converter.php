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

namespace Ignaszak\Router\Matcher;

use Ignaszak\Router\RouterException;

class Converter
{

    /**
     *
     * @var array
     */
    private $routeArray = [];

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
    private $convertedRouteArray = [];

    /**
     *
     * @param array $routeArray
     * @return array
     */
    public function convert(array $routeArray): array
    {
        $this->routeArray = $routeArray;
        $this->transformToRegex();
        $this->sort();
        return $this->convertedRouteArray;
    }

    private function sort()
    {
        uasort(
            $this->convertedRouteArray,
            function ($a, $b) {
                return strlen($b['path']) <=> strlen($a['path']);
            }
        );
    }

    private function transformToRegex()
    {
        $patternArray = $this->getPatterns();
        $tokenArray = $this->routeArray['tokens'] ?? [];
        $routeArray = $this->routeArray['routes'] ?? [];

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
            $this->convertedRouteArray[$name] = $route;
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
        $patternArray = $this->routeArray['patterns'] ?? [];
        foreach ($patternArray as $key => $value) {
            $result["@{$key}"] = $value;
        }
        return array_merge($this->patternArray, $result);
    }
}
