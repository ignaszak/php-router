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
use Ignaszak\Router\Interfaces\IFormatterStart;
use Ignaszak\Router\Route;

class RouteFormatter extends IRouteParser implements IFormatterStart
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
        'default' => '(.*)',
        'dot'     => '\.',
        'digit'   => '\d*',
        'alpha'   => '[A-Za-z_-]*',
        'alnum'   => '[\w-]*'
    ];

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
     * @see \Ignaszak\Router\Interfaces\IRouteParser::getRouteArray()
     */
    public function getRouteArray(): array
    {
        $routeArray = $this->route->getRouteArray();
        foreach ($routeArray as $name => $route) {
            $pattern = $this->parseNoNamedRoutes($route);
            $pattern = $this->addTokens(
                $route['token'] ?? [],
                $pattern,
                ':'
            );
            $pattern = $this->addTokens(
                $this->route->getTokenArray() ?? [],
                $pattern,
                ':'
            );
            $pattern = $this->addTokens($this->patternArray, $pattern, '@');
            $pattern = $this->preparePattern($pattern);

            $this->validRoute($pattern, $name);
            $routeArray[$name]['pattern'] = $pattern;
        }
        return $routeArray;
    }

    /**
     *
     * @param array $route
     * @return string
     */
    private function parseNoNamedRoutes(array $route): string
    {
        $result = preg_replace_callback(
            "/@?\\b(?<!:)[\\w@,.{|}()*+=?<>\\\\]+/",
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
     * @param array $token
     * @param string $pattern
     * @param string $symbol
     * @return string
     */
    private function addTokens(
        array $token,
        string $pattern,
        string $symbol
    ): string {
        if (empty($token)) {
            return $pattern;
        }

        $p = [];
        $r = [];

        foreach ($token as $key => $value) {
            $p[] = "/{$symbol}{$key}/";
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
