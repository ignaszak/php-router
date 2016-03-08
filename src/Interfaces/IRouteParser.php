<?php
namespace Ignaszak\Router\Interfaces;

abstract class IRouteParser
{

    /**
     *
     * @var string[]
     */
    public static $request = [];

    /**
     * @return array
     */
    abstract public function getRouteArray(): array;

    /**
     * @return array
     */
    abstract public function getTokenArray(): array;
}
