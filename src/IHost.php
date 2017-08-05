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

/**
 * Interface IHost
 * @package Ignaszak\Router
 */
interface IHost
{

    /**
     * @return string
     */
    public function getBaseURL(): string;

    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return string
     */
    public function getHttpMethod(): string;
}
