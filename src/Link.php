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
     * @var IFormatterLink
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
     * @param IFormatterLink $formatter
     */
    public function set(IFormatterLink $formatter, Host $host = null)
    {
        $this->formatter = $formatter;
        $this->baseURL = ! is_null($host) ? $host->getBaseURL() : '';
    }

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @return string
     */
    public function getLink(string $name, array $replacement): string
    {
        $route = $this->formatter->getRoute()->getRouteArray()[$name];
        $localTokens = $route['token'] ?? [];
        $globalTokens = $this->formatter->getTokenArray() ?? [];
        $tokenPattern = [];

        foreach ($replacement as $token => $value) {
            $pattern = @$localTokens[$token] ?? @$globalTokens[$token];

            if (! empty($pattern)) {
                $regEx = $this->replacePattern($pattern);

                if (! preg_match("/^{$regEx}$/", (string)$value)) {
                    throw new \RuntimeException(
                        "Value '{$value}' don't match token '{$token}' ({$pattern}) in route '{$name}'"
                    );
                }

                $tokenPattern[] = "{{$token}}";
            }
        }

        $link = $this->baseURL . str_replace(
            $tokenPattern,
            $replacement,
            $route['pattern']
        );

        $this->validLink($link, $name);

        return $link;
    }

    /**
     *
     * @param string $pattern
     * @return string
     */
    private function replacePattern(string $pattern): string
    {
        $name = [];
        $patternArray = $this->formatter->getPatternArray();
        foreach ($patternArray as $key => $value) {
            $name[] = "@{$key}";
        }
        return str_replace($name, $patternArray, $pattern);
    }

    /**
     *
     * @param string $route
     * @param string $name
     * @throws \RuntimeException
     * @return boolean
     */
    private function validLink(string $route, string $name): bool
    {
        $m = [];
        if (preg_match_all("/{[a-z]+}/", $route, $m)) {
            throw new \RuntimeException(
                "Detect unadded tokens: " .
                implode(', ', $m[0]) . " in route '{$name}'"
            );
        } else {
            return true;
        }
    }
}
