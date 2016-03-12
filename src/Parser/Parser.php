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
     * @var IRouteParser
     */
    private $route;

    /**
     *
     * @param IRouteParser $route
     */
    public function __construct(IRouteParser $route)
    {
        $this->route = $route;
    }

    public function run()
    {
        foreach ($this->route->getRouteArray() as $name => $route) {
            $m = [];
            if (preg_match($route['pattern'], Conf::getQueryString(), $m, PREG_OFFSET_CAPTURE)) {
                $attachment = $route['attachment'] ?? '';
                $callAttachment = $route['callAttachment'] ?? false;
                $request = [
                    'name' => $name,
                    'controller' => $route['controller'] ?? '',
                    'callAttachment' => $callAttachment,
                    'attachment' => $attachment,
                    'routes' => $this->formatArray($m)
                ];

                IRouteParser::$request = $request;
                $this->callAttachment($request);
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
     * @param array $request
     */
    private function callAttachment(array $request)
    {
        if ($request['callAttachment']) {
            call_user_func_array($request['attachment'], $request['routes']);
        }
    }
}
