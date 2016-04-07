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

namespace Ignaszak\Router;

use Ignaszak\Router\Interfaces\IRoute;
use Symfony\Component\Yaml\Parser;

class Yaml implements IRoute
{

    /**
     *
     * @var Parser
     */
    private $parser;

    /**
     *
     * @var array
     */
    private $routeArray = [];

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRoute::getRouteArray()
     */
    public function getRouteArray(): array
    {
        return $this->routeArray;
    }

    /**
     *
     * @param string $file
     */
    public function add(string $file)
    {
        if (! is_file($file) && ! is_readable($file)) {
            throw new RouterException(
                "The file '{$file}' does not exists or is not readable"
            );
        } else {
            $route = $this->parser->parse(file_get_contents($file));
            $duplicateNames = array_diff_key($route, $this->routeArray);
            if ($route !== $duplicateNames) {
                throw new RouterException(
                    "Route '" . implode(
                        "', '",
                        array_keys(array_diff_key($route, $duplicateNames))
                    ) . "' alredy exists in '{$file}'"
                );
            } else {
                $this->routeArray = array_merge($this->routeArray, $route);
            }
        }
    }
}
