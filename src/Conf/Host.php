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

namespace Ignaszak\Router\Conf;

/**
 * Class defines current query from $_SERVER['REQUEST_URI']
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class Host
{

    /**
     *
     * @var string
     */
    private $baseURI = '';

    /**
     *
     * @var string
     */
    private $base = '';

    /**
     *
     * @param string $baseURI
     */
    public function validBaseURI(string $baseURI)
    {
        if (empty($baseURI)) {
            $this->baseURI = $this->addSlashToURI(
                $this->getBaseURIFromServerName()
            );
        } else {
            $this->baseURI = $this->addSlashToURI($baseURI);
            $this->base = $this->replaceURI($this->baseURI);
        }
    }

    /**
     *
     * @return string
     */
    public function getBaseURI(): string
    {
        return $this->baseURI;
    }

    /**
     *
     * @return string
     */
    private function getBaseURIFromServerName(): string
    {
        return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' .
            "{$_SERVER['SERVER_NAME']}/";
    }

    /**
     * Returns current query string from $_SERVER['REQUEST_URI']
     *
     * @return string
     */
    public function getQueryString(): string
    {
        $requestURI = $_SERVER['REQUEST_URI'];
        $baseRequestURI = $this->baseRequestURI();
        return $requestURI != $baseRequestURI ?
            substr($requestURI, strlen($baseRequestURI) - strlen($requestURI)) :
            '';
    }

    /**
     * If baseURI is defined, returns baseURI without server name
     *
     * @return string
     */
    private function baseRequestURI(): string
    {
        if (! empty($this->base)) {
            return str_replace(
                $this->replaceURI($_SERVER['SERVER_NAME']),
                '',
                $this->base
            );
        }
        return '';
    }

    /**
     * Removes protocols and replaces locals ip to 'localhost'
     *
     * @param string $uri
     * @return string
     */
    private function replaceURI(string $uri): string
    {
        return preg_replace(
            ['/^(https?:\/\/)|(www\.)/', '/^(192\.168\.1\..)|(127\.0\.0\.1)/'],
            ['', 'localhost'],
            $uri
        );
    }

    /**
     * Adds slash to the end of uri
     *
     * @param string $uri
     * @return string
     */
    private function addSlashToURI(string $uri): string
    {
        return substr($uri, -1) == '/' ? $uri : "{$uri}/";
    }
}
