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

use Ignaszak\Router\Interfaces\IFormatterStart;
use Ignaszak\Router\Conf\Conf;

class Link
{

    /**
     *
     * @var Link
     */
    private static $link;

    /**
     *
     * @var string
     */
    private $baseURI = '';

    /**
     *
     * @param IFormatterStart|null $formatter
     */
    private function __construct()
    {
        $this->baseURI = Conf::instance()->baseURI;
    }

    /**
     *
     * @param IFormatterStart|null $formatter
     * @return Link
     */
    public static function instance(): Link
    {
        if (empty(self::$link)) {
            self::$link = new self();
        }

        return self::$link;
    }
}
