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

use Ignaszak\Router\Interfaces\IResponseStatic;

/**
 * Class provides methods for users to get matched routes
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 *
 */
class ResponseStatic implements IResponseStatic
{

    /**
     *
     * @var Response
     */
    public static $response;

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getName()
     */
    public static function getName(): string
    {
        return self::$response->getName();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getController()
     */
    public static function getController(): string
    {
        return self::$response->getController();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getGroup()
     */
    public static function getGroup(): string
    {
        return self::$response->getGroup();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getAttachment()
     */
    public static function getAttachment(): \Closure
    {
        return self::$response->getAttachment();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getParams()
     */
    public static function getParams(): array
    {
        return self::$response->getParams();
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getParam()
     */
    public static function getParam(string $token): string
    {
        return self::$response->getParam($token);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponseStatic::getLink()
     */
    public static function getLink(string $name, array $replacement): string
    {
        return self::$response->getLink($name, $replacement);
    }
}
