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

namespace Ignaszak\Router\Collection;

use Symfony\Component\Yaml\Parser;
use Ignaszak\Router\RouterException;

class Yaml implements IRoute
{

    /**
     *
     * @var Parser
     */
    private $parser = null;

    /**
     *
     * @var string[]
     */
    private $fileArray = [];

    /**
     *
     * @var string
     */
    private $fileMTime = '';

    public function __construct()
    {
        $this->parser = new Parser();
    }

    /**
     *
     * @param string $file
     */
    public function add(string $file)
    {
        if (! is_file($file) && ! is_readable($file)) {
            throw new RouterException(
                "The file '{$file}' does not exists or is not readable"
            );
        } else {
            $this->fileArray[] = $file;
            $this->fileMTime .= filemtime($file);
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRoute::getRouteArray()
     */
    public function getRouteArray(): array
    {
        $result = [];
        foreach ($this->fileArray as $file) {
            $result = array_merge(
                $result,
                $this->parser->parse(file_get_contents($file))
            );
        }
        return $result;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Ignaszak\Router\Interfaces\IRoute::getChecksum()
     */
    public function getChecksum(): string
    {
        return md5($this->fileMTime);
    }
}
