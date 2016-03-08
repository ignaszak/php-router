<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
declare(strict_types=1);

namespace Ignaszak\Router\Conf;

/**
 * Class defines current query from $_SERVER['REQUEST_URI']
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Host.php
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
     * @param string $baseURI
     */
    public function validBaseURI(string $baseURI)
    {
        if (! empty($baseURI)) {
            $this->baseURI = $this->addSlashToURI($this->replaceURI($baseURI));
        }
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
        if (! empty($this->baseURI)) {
            return str_replace(
                $this->replaceURI($_SERVER['SERVER_NAME']),
                '',
                $this->baseURI
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
