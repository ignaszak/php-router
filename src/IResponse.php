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
 * Interface IResponse
 * @package Ignaszak\Router
 */
interface IResponse
{

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function controller(): string;

    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $token
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $token, $default = null);

    /**
     * @return string
     */
    public function group(): string;

    /**
     * @param string $token
     *
     * @return bool
     */
    public function has(string $token): bool;

    /**
     * @return array
     */
    public function tokens(): array;
}
