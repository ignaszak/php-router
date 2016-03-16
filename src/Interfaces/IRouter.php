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

use Ignaszak\Router\Conf\Host;

interface IRouter
{

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouter
     */
    public function addToken(string $name, string $pattern): IRouter;

    /**
     *
     * @param array $patterns
     * @return \Ignaszak\Router\Interfaces\IRouter
     */
    public function addTokens(array $patterns): IRouter;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouter
     */
    public function addPattern(string $name, string $pattern): IRouter;

    /**
     *
     * @param array $patterns
     * @return \Ignaszak\Router\Interfaces\IRouter
     */
    public function addPatterns(array $patterns): IRouter;

    /**
     *
     * @param Host $host
     * @param string $query
     * @param string $httpMethod
     */
    public function run(
        Host $host = null,
        string $query = '',
        string $httpMethod = ''
    );
}
