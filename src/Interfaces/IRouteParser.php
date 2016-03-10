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

abstract class IRouteParser
{

    /**
     *
     * @var string[]
     */
    public static $request = [];

    /**
     *
     * @return array
     */
    abstract public function getRouteArray(): array;
}
