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

namespace Ignaszak\Router;

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
    private $baseQuery = '';

    /**
     *
     * @param string $baseQuery
     */
    public function __construct(string $baseQuery = '')
    {
        $this->baseQuery = $baseQuery;
    }

    /**
     *
     * @return string
     */
    public function getBaseURL(): string
    {
        $serverName = $_SERVER['SERVER_NAME'] ?? '';
        $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') .
            "://{$serverName}";
        return !empty($this->baseQuery) ? $url . $this->baseQuery : $url;
    }

    /**
     *
     * @return string
     */
    public function getQuery(): string
    {
        $requestURI = $_SERVER['REQUEST_URI'] ?? '';
        if (empty($this->baseQuery)) {
            return $requestURI;
        } else {
            return $requestURI != $this->baseQuery ?
                (string)substr(
                    $requestURI,
                    strlen($this->baseQuery) - strlen($requestURI)
                ) : '';
        }
    }

    /**
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}
