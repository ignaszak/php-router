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

use Ignaszak\Router\Host;

class UrlGenerator
{

    /**
     *
     * @var array
     */
    private $convertedRouteArray = [];

    /**
     *
     * @var string
     */
    private $baseUrl = '';

    /**
     *
     * @param IRoute $route
     * @param Host $host
     */
    public function __construct(Collection\IRoute $route, Host $host = null)
    {
        $this->convertedRouteArray = $route->getRouteArray();
        $this->baseUrl = ! is_null($host) ? $host->getBaseURL() : '';
    }

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @throws RouterException
     * @return string
     */
    public function url(string $name, array $replacement = []): string
    {
        if (! array_key_exists($name, $this->convertedRouteArray)) {
            throw new RouterException("Route '{$name}' does not exist");
        }
        $route = $this->convertedRouteArray[$name];
        $search = [];
        $replace = [];
        foreach ($route['tokens'] as $token => $pattern) {
                $value = $replacement[$token] ??
                    $route['defaults'][$token] ?? null;
                if (is_null($value)) {
                    throw new RouterException(
                        "Missed token {{$token}} `{$pattern}` in route '{$name}'"
                    );
                } elseif (! preg_match($pattern, (string) $value)) {
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
        return $this->baseUrl . $link;
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
