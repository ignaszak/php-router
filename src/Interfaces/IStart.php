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
 * Start class interface
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Interfaces/IStart.php
 *
 */
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

    public function run();
}
