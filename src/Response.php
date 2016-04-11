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
    public function getName(): string
    {
        return $this->response['name'] ?? '';
    }

    /**
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->response['controller'] ?? '';
    }

    /**
     *
     * @return \Closure
     */
    public function getAttachment(): \Closure
    {
        return @$this->response['attachment'] instanceof \Closure ?
            $this->response['attachment'] : function () {
            };
    }

    /**
     *
     * @return string[]
     */
    public function getParams(): array
    {
        return $this->response['params'] ?? [];
    }

    /**
     *
     * @param string $route
     * @return string
     */
    public function getParam(string $token): string
    {
        return $this->response['params'][$token] ?? '';
    }

    /**
     *
     * @return string
     */
    public function getGroup(): string
    {
        return $this->response['group'] ?? '';
    }
}
