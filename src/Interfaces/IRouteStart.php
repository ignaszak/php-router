<?php
namespace Ignaszak\Router\Interfaces;

interface IRouteStart
{

    /**
     *
     * @param string $name
     * @param string $pattern
     * @return \Ignaszak\Router\Interfaces\IRouteStart
     */
    public function addToken(string $name, string $pattern): IRouteStart;
}
