<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.08.17
 * Time: 23:40
 */

declare(strict_types=1);

namespace Ignaszak\Router;

/**
 * Interface IResponse
 * @package Ignaszak\Router
 */
interface IResponse {

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
