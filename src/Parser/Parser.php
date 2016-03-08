<?php
declare(strict_types=1);

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Conf\Conf;
use Ignaszak\Router\Interfaces\IRouteParser;

class Parser
{

    private static $counter = 0;

    /**
     *
     * @var Route
     */
    private $route;

    /**
     *
     * @param Route $route
     */
    public function __construct(IRouteParser $route)
    {
        $this->route = $route;
    }

    public function run()
    {
        $request = [];
        foreach ($this->route->getRouteArray() as $name => $route) {
            $pattern = $this->parseNoNamedRoutes($route);
            $pattern = $this->addTokens($route['token'], $pattern);
            $pattern = $this->addTokens($this->route->getTokenArray(), $pattern);
            $pattern = $this->preparePattern($pattern);
            $m = [];
            if (preg_match($pattern, Conf::getQueryString(), $m)) {
                $request = array_merge(
                    [
                        'name' => $name,
                        'controller' => $route['controller'] ?? ''
                    ],
                    $m
                );
                IRouteParser::$request = $this->formatArray($request);
                return;
            }
        }
    }

    /**
     *
     * @param array $route
     * @return string
     */
    private function parseNoNamedRoutes(array $route): string
    {
        $result = preg_replace_callback(
            "/\\b(?<!:)[a-zA-Z0-9_(),{}]*/",
            function ($m) {
                return empty($m[0]) ? "" :
                    "(?P<route" . ++self::$counter .">{$m[0]})";
            },
            $route['pattern']
        );
        self::$counter = 0;
        return $result;
    }

    /**
     *
     * @param string[] $token
     * @param string $pattern
     * @return string
     */
    private function addTokens(array $token, string $pattern): string
    {
        $p = [];
        $r = [];

        foreach ($token as $key => $value) {
            $p[] = "/{$key}/";
            $subpattern = substr($key, 1);
            $r[] = "(?P<{$subpattern}>{$value})";
        }

        return preg_replace($p, $r, $pattern);
    }

    /**
     *
     * @param string $pattern
     * @return string
     */
    private function preparePattern(string $pattern): string
    {
        return '/' . str_replace(['/', '.'], ['\/', '\.'], $pattern) . '/';
    }

    /**
     *
     * @param array $array
     * @return string[]
     */
    private function formatArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_int($key) || empty($value)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
