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
use Ignaszak\Router\Parser\RouteFormatter;

class Link
{

    /**
     *
     * @var Link
     */
    private static $link;

    /**
     *
     * @var RouteFormatter
     */
    private $formatter;

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
    public function set(RouteFormatter $formatter, Host $host = null)
    {
        $this->formatter = $formatter;
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
        $route = $this->formatter->getRouteArray()[$name];
        $search = [];
        $replace = [];
        foreach ($route['token'] as $token => $pattern) {
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
        return $link;
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
