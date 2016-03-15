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

use Ignaszak\Router\Route;

interface IFormatterLink
{

    /**
     *
     * @return \Ignaszak\Router\Route
     */
    public function getRoute(): Route;

    /**
     *
     * @return string[]
     */
    public function getPatternArray(): array;

    /**
     *
     * @return string[]
     */
    public function getTokenArray(): array;
}
