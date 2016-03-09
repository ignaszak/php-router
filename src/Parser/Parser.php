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

use Ignaszak\Router\Conf\Conf;
use Ignaszak\Router\Interfaces\IRouteParser;

class Parser
{

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
        foreach ($this->route->getRouteArray() as $name => $route) {
            $m = [];
            if (preg_match($route['pattern'], Conf::getQueryString(), $m)) {
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
        IRouteParser::$request = [];
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
