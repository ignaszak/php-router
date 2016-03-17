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

interface IResponse
{

    /**
     *
     * @return string
     */
    public function getName(): string;

    /**
     *
     * @return string
     */
    public function getController(): string;

    /**
     *
     * @return \Closure
     */
    public function getAttachment(): \Closure;

    /**
     *
     * @return string[]
     */
    public function getParams(): array;

    /**
     *
     * @param string $route
     * @return string
     */
    public function getParam(string $route): string;

    /**
     *
     * @return string
     */
    public function getGroup(): string;

    /**
     *
     * @param string $name
     * @param string[] $replacement
     * @return string
     */
    public function getLink(string $name, array $replacement): string;
}
