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

namespace Ignaszak\Router\Interfaces;

interface IStart
{

    /**
     *
     * @return \Ignaszak\Router\Interfaces\IStart
     */
    public static function instance(): IStart;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function add(string $name, string $pattern): IRouteAdd;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addToken(string $name, string $pattern): IRouteStart;

    /**
     *
     * @param string[] $tokens
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addTokens(array $tokens): IRouteStart;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IFormatterStart
     */
    public function addPattern(string $name, string $pattern): IFormatterStart;

    /**
     *
     * @param array $patterns
     * @return \Ignaszak\Router\Interfaces\IFormatterStart
     */
    public function addPatterns(array $patterns): IFormatterStart;

    public function run();
}
