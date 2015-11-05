<?php

namespace Ignaszak\Router;

class Host
{

    private $host;

    public function __construct($host = '')
    {
        if (!empty($host))
            $this->host = $this->removeProtocol($host);
    }

    public function getQueryString()
    {
        return ($_SERVER['REQUEST_URI'] != $this->baseRequestURI() ?
            substr($_SERVER['REQUEST_URI'], strlen($this->baseRequestURI()) - strlen($_SERVER['REQUEST_URI'])) :
            "");
    }

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

    private function removeProtocol($url)
    {
        return preg_replace(
            array('/^(http:\/\/)/', '/^(https:\/\/)/', '/^www\./', '/^192\.168\.1\.*/', '/^127\.0\.0\.1/'),
            array('', '', '', 'localhost', 'localhost'),
            $url
        );
    }

}
