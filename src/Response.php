<?php
namespace Ignaszak\Router;

use Ignaszak\Router\Interfaces\IResponse;

class Response implements IResponse
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
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getName()
     */
    public function getName(): string
    {
        return $this->response['name'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getController()
     */
    public function getController(): string
    {
        return $this->response['controller'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getAttachment()
     */
    public function getAttachment(): \Closure
    {
        return @$this->response['attachment'] instanceof \Closure ?
            $this->response['attachment'] : function () {
            };
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getParams()
     */
    public function getParams(): array
    {
        return $this->response['params'] ?? [];
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getParam()
     */
    public function getParam(string $token): string
    {
        return $this->response['params'][$token] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getGroup()
     */
    public function getGroup(): string
    {
        return $this->response['group'] ?? '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IResponse::getLink()
     */
    public function getLink(string $name, array $replacement): string
    {
        return Link::instance()->getLink($name, $replacement);
    }
}
