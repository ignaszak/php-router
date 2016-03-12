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

interface IClient
{

    /**
     *
     * @param string $route
     * @return string
     */
    public static function getRoute(string $route): string;

    /**
     *
     * @return array
     */
    public static function getRoutes(): array;

    /**
     *
     * @return string
     */
    public static function getName(): string;

    /**
     *
     * @return string
     */
    public static function getController(): string;

    /**
     *
     * @param string $name
     * @param array $replacement
     * @return string
     */
    public static function getLink(string $name, array $replacement): string;
}
