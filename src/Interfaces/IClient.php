<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Ignaszak\Router\Interfaces;

/**
 * Client class interface
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Interfaces/IClient.php
 *
 */
interface IClient
{

    /**
     * @param string $route
     */
    public static function getRoute($route = '');

    public static function getAllRoutes();

    public static function getRouteName();

    /**
     * @param string $name
     */
    public static function isRouteName($name);

    public static function getDefaultRoute();
}
