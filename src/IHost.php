<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 02.08.17
 * Time: 23:35
 */

declare(strict_types=1);

namespace Ignaszak\Router;

/**
 * Interface IHost
 * @package Ignaszak\Router
 */
interface IHost {

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
