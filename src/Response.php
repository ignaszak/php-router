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

/**
 * Class Response
 * @package Ignaszak\Router
 */
class Response implements IResponse
{

    /**
     * @var array
     */
    private $response = [];

    /**
     * Response constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->response['name'] ?? '';
    }

    /**
     * @return string
     */
    public function controller(): string
    {
        return $this->response['controller'] ?? '';
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->response['params'] ?? [];
    }

    /**
     * @param string $token
     * @param null $default
     *
     * @return null
     */
    public function get(string $token, $default = null)
    {
        return $this->response['params'][$token] ?? $default;
    }

    /**
     * @return string
     */
    public function group(): string
    {
        return $this->response['group'] ?? '';
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function has(string $token): bool
    {
        return array_key_exists($token, $this->response['params']);
    }

    /**
     * @return array
     */
    public function tokens(): array
    {
        return array_keys($this->response['params']);
    }
}
