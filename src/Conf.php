<?php

namespace Ignaszak\Router;

class Conf
{

    private static $_conf;
    private $baseURL;
    private $defaultRoute;

    public static function instance()
    {
        if (empty(self::$_conf))
            self::$_conf = new Conf;

        return self::$_conf;
    }

    public function setProperty($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public static function get($property)
    {
        if (property_exists(self::$_conf, $property)) {
            return self::$_conf->$property;
        }
    }

    public static function getQueryString()
    {
        $baseURL = self::$_conf->baseURL;

        if (!filter_var($baseURL, FILTER_VALIDATE_URL) === false) {

            $serverName = sprintf(
                "%s://%s",
                isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                $_SERVER['SERVER_NAME']
                );

            $baseRequestURI = str_replace($serverName, '', $baseURL);
            $requestURI = $_SERVER['REQUEST_URI'];

            return ($requestURI != $baseRequestURI ?
                substr($requestURI, strlen($baseRequestURI) - strlen($requestURI)) :
                "");

        } else {

            throw new Exception("$baseURL is not a valid URL");

        }
    }

}
