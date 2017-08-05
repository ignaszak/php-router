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

namespace Ignaszak\Router\Collection;

/**
 * Interface IRouteStart
 * @package Ignaszak\Router\Collection
 */
interface IRouteStart
{

    /**
     * @param string|null $name
     * @param string $pattern
     * @param string $method
     *
     * @return IRouteAdd
     */
    public function add(
        string $name = null,
        string $pattern,
        string $method = ''
    ): IRouteAdd;

    /**
     * @param string|null $name
     * @param string $pattern
     *
     * @return IRouteAdd
     */
    public function get(string $name = null, string $pattern): IRouteAdd;

    /**
     * @param string|null $name
     * @param string $pattern
     *
     * @return IRouteAdd
     */
    public function post(string $name = null, string $pattern): IRouteAdd;

    /**
     * @param string $name
     *
     * @return IRouteStart
     */
    public function group(string $name): IRouteStart;

    /**
     * @param array $tokens
     *
     * @return IRouteStart
     */
    public function addTokens(array $tokens): IRouteStart;

    /**
     * @param array $defaults
     *
     * @return IRouteStart
     */
    public function addDefaults(array $defaults): IRouteStart;

    /**
     * @param array $patterns
     *
     * @return IRouteStart
     */
    public function addPatterns(array $patterns): IRouteStart;
}
