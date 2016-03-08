<?php
namespace Ignaszak\Router\Interfaces;

interface IRouteAdd
{

    /**
     *
     * @param string $controller
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function controller(string $controller): IRouteAdd;

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteAdd
     */
    public function token(string $name, string $pattern): IRouteAdd;
}
