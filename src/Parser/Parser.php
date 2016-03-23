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

use Ignaszak\Router\Conf\Host;

class Parser
{

    /**
     *
     * @var RouteFormatter
     */
    private $formatter;

    /**
     *
     * @param IRouteParser $formatter
     */
    public function __construct(RouteFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     *
     * @param Host $host
     * @param string $query
     * @param string $httpMethod
     */
    public function run(
        Host $host = null,
        string $query = '',
        string $httpMethod = ''
    ): array {
        $m = [];
        if (! is_null($host)) {
            $query = $host->getQuery();
            $httpMethod = $host->getHttpMethod();
        }

        foreach ($this->formatter->getRouteArray() as $name => $route) {
            if (preg_match(
                $route['pattern'],
                $query,
                $m,
                PREG_OFFSET_CAPTURE
            ) &&
                $this->httpMethod($route['method'] ?? '', $httpMethod)
            ) {
                $controller = $route['controller'] ?? '';
                $routes = $this->formatArray($m);
                $request = [
                    'name' => $name,
                    'controller' => $this->matchController(
                        $controller,
                        $routes
                    ),
                    'callAttachment' => $route['callAttachment'] ?? false,
                    'attachment' => $route['attachment'] ?? '',
                    'params' => $routes,
                    'group' => $route['group']
                ];
                $this->callAttachment($request);
                return $request;
            }
        }
        return [];
    }

    /**
     *
     * @param string $routeMethod
     * @param string $currentMethod
     * @return boolean
     */
    private function httpMethod(
        string $routeMethod,
        string $currentMethod
    ): bool {
        if (empty($routeMethod) ||
            strpos($routeMethod, $currentMethod) !== false
        ) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $array
     * @return string[]
     */
    private function formatArray(array $array): array
    {
        unset($array[0]);
        $multi = array_map(
            'unserialize',
            array_unique(array_map('serialize', $array))
        );
        $return = [];
        foreach ($multi as $key => $value) {
            if (is_int($key)) {
                $return[] = $value[0];
            } else {
                $return[$key] = $value[0];
            }
        }
        return $return;
    }

    /**
     *
     * @param string $controller
     * @param string[] $routes
     * @return string
     */
    private function matchController(string $controller, array $routes): string
    {
        $pattern = [];
        foreach ($routes as $key => $value) {
            $pattern[] = "{{$key}}";
        }
        return str_replace($pattern, $routes, $controller);
    }

    /**
     *
     * @param array $request
     */
    private function callAttachment(array $request)
    {
        if ($request['callAttachment']) {
            call_user_func_array($request['attachment'], $request['params']);
        }
    }
}
