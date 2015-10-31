<?php

namespace Ignaszak\Router\Interfaces;

interface IClient
{

    public static function getRoute($route = '');
    public static function getRouteArray(array $route);
    public static function getAllRoutes();
    public static function getRouteName();
    public static function isRouteName($name);
    public static function getDefaultRoute();
    public static function getControllerFile();

}
