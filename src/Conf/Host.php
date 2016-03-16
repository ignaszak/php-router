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
        $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') .
            "://{$_SERVER['SERVER_NAME']}";
        return !empty($this->baseQuery) ? $url . $this->baseQuery : $url;
    }

    /**
     *
     * @return string
     */
    public function getQuery(): string
    {
        if (empty($this->baseQuery)) {

            return $_SERVER['REQUEST_URI'];

        } else {

            return $_SERVER['REQUEST_URI'] != $this->baseQuery ?
                substr(
                    $_SERVER['REQUEST_URI'],
                    strlen($this->baseQuery) - strlen($_SERVER['REQUEST_URI'])
                ) : '';

        }
    }

    /**
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
