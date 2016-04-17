<?php
namespace Ignaszak\Router;

class Response
{

    /**
     *
     * @var array
     */
    private $response = [];

    /**
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     *
     * @return string
     */
    public function name(): string
    {
        return $this->response['name'] ?? '';
    }

    /**
     *
     * @return string
     */
    public function controller(): string
    {
        return $this->response['controller'] ?? '';
    }

    /**
     *
     * @return \Closure
     */
    public function attachment(): \Closure
    {
        return @$this->response['attachment'] instanceof \Closure ?
            $this->response['attachment'] : function () {
            };
    }

    /**
     *
     * @return string[]
     */
    public function all(): array
    {
        return $this->response['params'] ?? [];
    }

    /**
     *
     * @param string $token
     * @param mixed $default
     * @return string|mixed
     */
    public function get(string $token, $default = null): string
    {
        return $this->response['params'][$token] ?? $default;
    }

    /**
     *
     * @return string
     */
    public function group(): string
    {
        return $this->response['group'] ?? '';
    }

    /**
     *
     * @param string $token
     * @return boolean
     */
    public function has(string $token): bool
    {
        return array_key_exists($token, $this->response['params']);
    }

    /**
     *
     * @return string[]
     */
    public function tokens(): array
    {
        return array_keys($this->response['params']);
    }
}
