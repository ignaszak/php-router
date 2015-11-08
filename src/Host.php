<?php

namespace Ignaszak\Router;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Host.php
 *
 */
class Host
{

    /**
     * @var string
     */
    private $host;

    /**
     * @param string $host
     */
    public function __construct($host = '')
    {
        if (!empty($host))
            $this->host = $this->removeProtocol($host);
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return ($_SERVER['REQUEST_URI'] != $this->baseRequestURI() ?
            substr($_SERVER['REQUEST_URI'], strlen($this->baseRequestURI()) - strlen($_SERVER['REQUEST_URI'])) :
            "");
    }

    /**
     * @return string
     */
    private function baseRequestURI()
    {
        if (!empty($this->host)) {
            return str_replace(
                $this->removeProtocol($_SERVER['SERVER_NAME']),
                '',
                $this->host
            );
        }
    }

    /**
     * @param string $url
     * @return string
     */
    private function removeProtocol($url)
    {
        return preg_replace(
            array('/^(http:\/\/)/', '/^(https:\/\/)/', '/^www\./', '/^192\.168\.1\../', '/^127\.0\.0\.1/'),
            array('', '', '', 'localhost', 'localhost'),
            $url
        );
    }

}
