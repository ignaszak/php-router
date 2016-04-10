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

interface IRoute
{

    /**
     *
     * @return array
     */
    public function getRouteArray(): array;

    /**
     *
     * @return string
     */
    public function getChecksum(): string;
}
