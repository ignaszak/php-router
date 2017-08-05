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
 * Interface IRouteAdd
 * @package Ignaszak\Router\Collection
 */
interface IRouteAdd
{

    /**
     * @param string $controller
     *
     * @return IRouteAdd
     */
    public function controller(string $controller): IRouteAdd;

    /**
     * @param array $tokens
     *
     * @return IRouteAdd
     */
    public function tokens(array $tokens): IRouteAdd;

    /**
     * @param array $defaults
     *
     * @return IRouteAdd
     */
    public function defaults(array $defaults): IRouteAdd;

    /**
     * @param \Closure $closure
     *
     * @return IRouteAdd
     */
    public function attach(\Closure $closure): IRouteAdd;
}
