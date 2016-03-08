<?php
declare(strict_types=1);

namespace Ignaszak\Router;

class Route
{

    /**
     *
     * @var string[]
     */
    public static $request = [];

    /**
     * Stores added routes
     *
     * @var array
     */
    private $routeArray = [];

    /**
     * Stores added tokens name as key and token pattern as value
     *
     * @var array
     */
    private $tokenArray = [];

    /**
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     */
    public function add(string $name, string $pattern, string $controller = '')
    {
        $this->routeArray[] = [
            'name'       => $name,
            'pattern'    => $pattern,
            'controller' => $controller
        ];
    }

    /**
     * @return array
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     *
     * @param string $name
     * @param string $pattern
     */
    public function addToken(string $name, string $pattern)
    {
        $this->tokenArray[$name] = $pattern;
    }

    /**
     * @return array
     */
    public function getTokenArray(): array
    {
        return $this->tokenArray;
    }

    /**
     * Sorts route array
     */
    public function sort()
    {
        usort(
            $this->routeArray,
            function ($a, $b) {
                return $a['pattern'] <=> $b['pattern'];
            }
        );
    }
}
