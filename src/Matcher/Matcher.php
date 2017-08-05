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

use Ignaszak\Router\Collection\IRoute;
use Ignaszak\Router\IHost;
use Ignaszak\Router\Response;

/**
 * Class Matcher
 * @package Ignaszak\Router\Matcher
 */
class Matcher
{

    /**
     * @var IRoute
     */
    private $route = null;

    /**
     * Matcher constructor.
     *
     * @param IRoute $route
     */
    public function __construct(IRoute $route)
    {
        $this->route = $route;
    }

    /**
     * @param IHost|null $host
     * @param string $query
     * @param string $httpMethod
     *
     * @return array
     */
    public function match(
        IHost $host = null,
        string $query = '',
        string $httpMethod = ''
    ): array
    {
        $m = [];
        if (!is_null($host)) {
            $query = $host->getQuery();
            $httpMethod = $host->getHttpMethod();
        }

        foreach ($this->route->getRouteArray() as $name => $route) {
            if (preg_match(
                    $route['path'],
                    $query,
                    $m
                ) &&
                $this->httpMethod($route['method'] ?? '', $httpMethod)
            ) {
                $controller = $route['controller'] ?? '';
                $params = $this->createParamsArray($m, $route['tokens']);
                $request = [
                    'name' => $name,
                    'controller' => $this->matchController(
                        $controller,
                        $params
                    ),
                    'attachment' => $route['attachment'] ?? '',
                    'params' => $params,
                    'group' => $route['group'] ?? ''
                ];
                $this->callAttachment($request);

                return $request;
            }
        }

        return [];
    }

    /**
     * @param string $routeMethod
     * @param string $currentMethod
     *
     * @return bool
     */
    private function httpMethod(
        string $routeMethod,
        string $currentMethod
    ): bool
    {
        if (empty($routeMethod) ||
            strpos($routeMethod, $currentMethod) !== false
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array $matches
     * @param array $tokens
     *
     * @return array
     */
    private function createParamsArray(array $matches, array $tokens): array
    {
        $return = [];
        foreach ($tokens as $token => $value) {
            $return[$token] = $matches[$token] ?? '';
        }

        return $return;
    }

    /**
     * @param string $controller
     * @param array $routes
     *
     * @return string
     */
    private function matchController(
        string $controller,
        array $routes
    ): string
    {
        $pattern = [];
        foreach ($routes as $key => $value) {
            $pattern[] = "{{$key}}";
        }

        return str_replace($pattern, $routes, $controller);
    }

    /**
     * @param array $request
     */
    private function callAttachment(array $request)
    {
        if ($request['attachment'] instanceof \Closure) {
            $request['attachment'](new Response($request));
        }
    }
}
