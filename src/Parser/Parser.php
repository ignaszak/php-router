<?php
declare(strict_types=1);

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Route;
use Ignaszak\Router\Conf\Conf;

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
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function run()
    {
        $request = [];
        foreach ($this->route->getRouteArray() as $route) {
            $routeWithTokens = $this->addTokenToRoute($route['pattern']);
            $routeWithRegEx = $this->prepareRoute($routeWithTokens);
            $find = $this->parse($routeWithRegEx);
            if (! empty($find)) {
                $request = array_merge(
                    [
                        'name' => $route['name'],
                        'controller' => $route['controller'] ?? ''
                    ],
                    $find
                );
            }
        }

        Route::$request = $this->formatArray($request);
    }

    /**
     *
     * @param string $route
     * @return string
     */
    private function addTokenToRoute(string $route): string
    {
        $m = [];
        preg_match_all('/\{(\w+)\}/', $route, $m);
        $tokenArray = $this->route->getTokenArray();
        foreach ($m[1] as $token) {
            $route = str_replace(
                "{{$token}}",
                "{{$token}:{$tokenArray[$token]}}",
                $route
            );
        }
        return $route;
    }

    /**
     * todo change regex
     * @param string $route
     * @return string
     */
    private function prepareRoute(string $route): string
    {
        $route = preg_replace_callback(
            "/\\b(?<!{)\\b(?<!\\()\\b(?<!\\[)[a-zA-Z0-9_\+]+(?!\\])\\b(?!\\))\\b(?!})\\b/",
            function ($m) {
                return "(?P<route" . ++self::$counter .">{$m[0]})";
            },
            $route
        );

        self::$counter = 0;

        return '/' . preg_replace(
            ["/\./", "/\//", "/{/", "/:/", "/}/"],
            ["\.", "\/", "(?P<", ">",")"],
            $route
        ) . '/';
    }

    /**
     *
     * @param string $pattern
     * @return string[]
     */
    private function parse(string $pattern): array
    {
        echo $pattern;
        $m = [];
        preg_match($pattern, Conf::getQueryString(), $m);
        return $m;
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
