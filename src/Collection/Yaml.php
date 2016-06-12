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
use Ignaszak\Router\Matcher\Converter;

class Yaml implements IRoute
{

    /**
     *
     * @var Converter
     */
    private $converter = null;

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
        $this->converter = new Converter();
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
        $result = [
            'routes' => [],
            'tokens' => [],
            'defaults' => [],
            'patterns' => []
        ];
        foreach ($this->fileArray as $file) {
            $array = $this->parser->parse(file_get_contents($file)) ?? [];
            $result['routes'] += ($array['routes'] ?? []);
            $result['tokens'] += ($array['tokens'] ?? []);
            $result['defaults'] += ($array['defaults'] ?? []);
            $result['patterns'] += ($array['patterns'] ?? []);
        }
        return $this->converter->convert($result);
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
