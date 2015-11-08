<?php

namespace Ignaszak\Router\Interfaces;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Interfaces/IClient.php
 *
 */
interface IClient
{

    /**
     * @param string $route
     */
    public static function getRoute($route = '');

    /**
     * @param array $route
     */
    public static function getRouteArray(array $route);

    public static function getAllRoutes();

    public static function getRouteName();

    /**
     * @param string $name
     */
    public static function isRouteName($name);

    public static function getDefaultRoute();

    public static function getControllerFile();

}
