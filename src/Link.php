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

use Ignaszak\Router\Conf\Host;

class Link
{

    /**
     *
     * @var Link
     */
    private static $link;

    /**
     *
     * @var array
     */
    private $formatedRouteArray = [];

    /**
     *
     * @var string
     */
    private $baseURL = '';

    private function __construct()
    {
    }

    /**
     *
     * @return Link
     */
    public static function instance(): Link
    {
        if (empty(self::$link)) {
            self::$link = new self();
        }

        return self::$link;
    }

    /**
     *
     * @param RouteFormatter $formatter
     * @param Host $host
     */
    public function set(array $formatedRouteArray, Host $host = null)
    {
        $this->formatedRouteArray = $formatedRouteArray;
        $this->baseURL = ! is_null($host) ? $host->getBaseURL() : '';
    }

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @throws RouterException
     * @return string
     */
    public function getLink(string $name, array $replacement): string
    {
        if (! array_key_exists($name, $this->formatedRouteArray)) {
            throw new RouterException("Route '{$name}' does not exist");
        }
        $route = $this->formatedRouteArray[$name];
        $search = [];
        $replace = [];
        foreach ($route['tokens'] as $token => $pattern) {
            $value = (string)$replacement[$token];
            if (! preg_match("/^{$pattern}$/", $value)) {
                throw new RouterException(
                    "Value '{$value}' don't match token {{$token}} `{$pattern}` in route '{$name}'"
                );
            }
            $search[] = "{{$token}}";
            $replace[] = $value;
        }
        $link = str_replace(
            ['\\', '?', '(', ')'],
            '',
            str_replace($search, $replace, $route['route'])
        );
        $this->validLink($link, $name);
        return $this->baseURL . $link;
    }

    /**
     *
     * @param string $link
     * @param string $name
     * @throws RouterException
     * @return boolean
     */
    private function validLink(string $link, string $name): bool
    {
        $m = [];
        if (preg_match_all("/\(\?P<(\w+)>/", $link, $m)) {
            throw new RouterException(
                "Detect unadded tokens: {" .
                implode('}, {', $m[1]) . "} in route '{$name}'"
            );
        } else {
            return true;
        }
    }
}
