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

interface IResponseStatic
{

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
     * @return \Closure
     */
    public static function getAttachment(): \Closure;

    /**
     *
     * @return string[]
     */
    public static function getParams(): array;

    /**
     *
     * @param string $route
     * @return string
     */
    public static function getParam(string $route): string;

    /**
     *
     * @return string
     */
    public static function getGroup(): string;

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @return string
     */
    public static function getLink(string $name, array $replacement): string;
}
