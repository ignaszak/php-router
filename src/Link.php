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

use Ignaszak\Router\Interfaces\IFormatterLink;
use Ignaszak\Router\Conf\Conf;

class Link
{

    /**
     *
     * @var Link
     */
    private static $link;

    /**
     *
     * @var Route
     */
    private $route;

    /**
     *
     * @var string
     */
    private $baseURI = '';

    /**
     *
     * @var string[]
     */
    private $patternArray = [];

    private function __construct()
    {
        $this->baseURI = Conf::instance()->baseURI;
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
     * @param IFormatterLink $formatter
     */
    public function set(IFormatterLink $formatter)
    {
        $this->route = $formatter->getRoute();
        $this->patternArray = $formatter->getPatternArray();
    }

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @return string
     */
    public function getLink(string $name, array $replacement): string
    {
        $route = $this->route->getRouteArray()[$name];
        $localTokens = $route['token'] ?? [];
        $globalTokens = $this->route->getTokenArray() ?? [];
        $tokenPattern = [];
        foreach ($replacement as $token => $value) {
            $pattern = ($localTokens[$token] ?? $globalTokens[$token]);
            $regEx = $this->replacePattern($pattern);
            if (! preg_match("/^{$regEx}$/", (string)$value)) {
                throw new \RuntimeException(
                    "Value '{$value}' don't match token '{$token}' ({$pattern}) in route '{$name}'"
                );
            }
            $tokenPattern[] = "{{$token}}";
        }

        return Conf::getBaseURI() .
            str_replace($tokenPattern, $replacement, $route['pattern']);
    }

    /**
     *
     * @param string $pattern
     * @return string
     */
    private function replacePattern(string $pattern): string
    {
        $name = [];
        foreach ($this->patternArray as $key => $value) {
            $name[] = "@{$key}";
        }
        return str_replace($name, $this->patternArray, $pattern);
    }
}
