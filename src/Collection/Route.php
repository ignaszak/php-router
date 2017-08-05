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

namespace Ignaszak\Router\Collection;

use Ignaszak\Router\RouterException;
use Ignaszak\Router\Matcher\Converter;

/**
 * Class Route
 * @package Ignaszak\Router\Collection
 */
class Route implements IRouteStart, IRouteAdd, IRoute
{

    /**
     * @var Converter
     */
    private $converter = null;

    /**
     * Stores added routes
     *
     * @var array
     */
    private $routesArray = [];

    /**
     * Global tokens
     *
     * @var string[]
     */
    private $tokensArray = [];

    /**
     * Global tokens default values
     *
     * @var array
     */
    private $defaultsArray = [];

    /**
     * Custom regex pattern
     *
     * @var string[]
     */
    private $patternsArray = [];

    /**
     * @var string
     */
    private $lastName = '';

    /**
     * @var string
     */
    private $group = '';

    /**
     * Route constructor.
     */
    private function __construct()
    {
        $this->converter = new Converter();
    }

    /**
     * @return IRouteStart
     */
    public static function start(): IRouteStart
    {
        return new Route();
    }

    /**
     * @return array
     */
    public function getRouteArray(): array
    {
        return $this->converter->convert([
            'routes' => $this->routesArray,
            'tokens' => $this->tokensArray,
            'defaults' => $this->defaultsArray,
            'patterns' => $this->patternsArray,
            'checksum' => $this->getChecksum()
        ]);
    }

    /**
     * @param string|null $name
     * @param string $pattern
     * @param string $method
     *
     * @return IRouteAdd
     * @throws RouterException
     */
    public function add(
        string $name = null,
        string $pattern,
        string $method = ''
    ): IRouteAdd
    {
        if (is_null($name)) {
            $this->routesArray[] = ['path' => $pattern];
            // Last array key
            $name = key(array_slice($this->routesArray, -1, 1, true));
        } else {
            if (array_key_exists($name, $this->routesArray)) {
                throw new RouterException(
                    "Route name '{$name}' alredy exists"
                );
            }
            $this->routesArray[$name] = ['path' => $pattern];
        }
        $this->routesArray[$name]['group'] = $this->group;
        $this->routesArray[$name]['method'] = $method;
        $this->lastName = $name;

        return $this;
    }

    /**
     * @param string|null $name
     * @param string $pattern
     *
     * @return IRouteAdd
     */
    public function get(string $name = null, string $pattern): IRouteAdd
    {
        $this->add($name, $pattern, 'GET');

        return $this;
    }

    /**
     * @param string|null $name
     * @param string $pattern
     *
     * @return IRouteAdd
     */
    public function post(string $name = null, string $pattern): IRouteAdd
    {
        $this->add($name, $pattern, 'POST');

        return $this;
    }

    /**
     * @param string $controller
     *
     * @return IRouteAdd
     */
    public function controller(string $controller): IRouteAdd
    {
        $this->routesArray[$this->lastName]['controller'] = $controller;

        return $this;
    }

    /**
     * @param array $tokens
     *
     * @return IRouteAdd
     */
    public function tokens(array $tokens): IRouteAdd
    {
        $this->routesArray[$this->lastName]['tokens'] =
            $this->routesArray[$this->lastName]['tokens'] ?? [] + $tokens;

        return $this;
    }

    /**
     * @param array $defaults
     *
     * @return IRouteAdd
     */
    public function defaults(array $defaults): IRouteAdd
    {
        $this->routesArray[$this->lastName]['defaults'] =
            $this->routesArray[$this->lastName]['defaults'] ?? [] + $defaults;

        return $this;
    }

    /**
     * @param \Closure $closure
     *
     * @return IRouteAdd
     */
    public function attach(\Closure $closure): IRouteAdd
    {
        $this->routesArray[$this->lastName]['attachment'] = $closure;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return IRouteStart
     */
    public function group(string $name = ''): IRouteStart
    {
        $this->group = $name;

        return $this;
    }

    /**
     * @param array $tokens
     *
     * @return IRouteStart
     */
    public function addTokens(array $tokens): IRouteStart
    {
        $this->tokensArray += $tokens;

        return $this;
    }

    /**
     * @param array $defaults
     *
     * @return IRouteStart
     */
    public function addDefaults(array $defaults): IRouteStart
    {
        $this->defaultsArray += $defaults;

        return $this;
    }

    /**
     * @param array $patterns
     *
     * @return IRouteStart
     */
    public function addPatterns(array $patterns): IRouteStart
    {
        $this->patternsArray += $patterns;

        return $this;
    }

    /**
     * @return string
     */
    public function getChecksum(): string
    {
        return md5(json_encode([
            'routes' => $this->routesArray,
            'tokens' => $this->tokensArray,
            'patterns' => $this->patternsArray
        ]));
    }
}
