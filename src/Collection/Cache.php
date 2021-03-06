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

use Ignaszak\Router\RouterException;

/**
 * Class Cache
 * @package Ignaszak\Router\Collection
 */
class Cache implements IRoute
{

    /**
     * @var IRoute
     */
    private $route = null;

    /**
     * @var string
     */
    private $tmpDir = __DIR__;

    /**
     * Cache constructor.
     *
     * @param IRoute $route
     */
    public function __construct(IRoute $route)
    {
        $this->route = $route;
    }

    /**
     * @param string $name
     * @param string $tmpDir
     *
     * @throws RouterException
     */
    public function __set(string $name, string $tmpDir)
    {
        if ($name != 'tmpDir') {
            throw new RouterException('Invalid property');
        } else {
            $this->tmpDir = $tmpDir;
        }
    }

    /**
     * @return string
     */
    public function getChecksum(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function getRouteArray(): array
    {
        $file = "{$this->tmpDir}/Ignaszak_Router_Tmp_Route.php";
        $tmpRoute = $this->loadTmpRoute($file);
        if (empty($tmpRoute)) {
            $routeArray = $this->route->getRouteArray();
            $this->saveTmpRoute($file, $routeArray);

            return $routeArray;
        } else {
            return $tmpRoute;
        }
    }

    /**
     * @param string $file
     *
     * @return array
     */
    private function loadTmpRoute(string $file): array
    {
        if (!is_file($file) || !is_readable($file)) {
            return [];
        } else {
            $tmpRoute = require_once $file;
            if ($tmpRoute['checksum'] != $this->route->getChecksum()) {
                return [];
            } else {
                return $tmpRoute['routes'];
            }
        }
    }

    /**
     * @param string $file
     * @param array $routes
     *
     * @throws RouterException
     */
    private function saveTmpRoute(string $file, array $routes)
    {
        $data = "<?php\n\nreturn [\n'checksum' => '" .
            $this->route->getChecksum() .
            "',\n'routes' => " . var_export($routes, true) . "\n];\n\n";
        if (!@file_put_contents($file, $data)) {
            throw new RouterException("Unable to save {$file}");
        }
    }
}
