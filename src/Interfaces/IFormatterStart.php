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

interface IFormatterStart
{

    public function format();

    public function sort();

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addToken(string $name, string $pattern): IFormatterStart;

    /**
     *
     * @param array $tokens
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addTokens(array $tokens): IFormatterStart;

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
}
