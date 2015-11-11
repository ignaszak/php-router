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

namespace Ignaszak\Router;

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
     * Stores base URL defined in Conf class
     * 
     * @var string|null
     */
    private $baseURL;

    /**
     * Sets base URL
     * 
     * @param string $baseURL
     */
    public function __construct($baseURL = '')
    {
        if (!empty($baseURL))
            $this->baseURL = $this->addSlashToURL($this->replaceURL($baseURL));
    }

    /**
     * Returns current query string from $_SERVER['REQUEST_URI']
     * 
     * @return string
     */
    public function getQueryString()
    {
        return ($_SERVER['REQUEST_URI'] != $this->baseRequestURI() ?
            substr($_SERVER['REQUEST_URI'], strlen($this->baseRequestURI()) - strlen($_SERVER['REQUEST_URI'])) :
            "");
    }

    /**
     * If baseURL is defined, returns baseURL without server name
     * 
     * @return string|null
     */
    private function baseRequestURI()
    {
        if (!empty($this->baseURL)) {
            return str_replace(
                $this->replaceURL($_SERVER['SERVER_NAME']),
                '',
                $this->baseURL
            );
        }
    }

    /**
     * Removes protocols and replaces locals ip to 'localhost'
     * 
     * @param string $url
     * @return string
     */
    private function replaceURL($url)
    {
        return preg_replace(
            array('/^(https?:\/\/)|(www\.)/', '/^(192\.168\.1\..)|(127\.0\.0\.1)/'),
            array('', 'localhost'),
            $url
        );
    }

    /**
     * Adds slash to the end of url
     * 
     * @param string $url
     * @return string
     */
    private function addSlashToURL($url)
    {
        return (substr($url, -1) == '/' ? $url : $url . '/');
    }

}
