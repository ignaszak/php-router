<?php
/**
 * phpDocumentor
 *
 * PHP Version 5.5
 *
 * @copyright 2015 Tomasz Ignaszak
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Conf;

/**
 * Matchs defined routes with current query
 *
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Parser/RouteParser.php
 *
 */
class RouteParser extends ParserStrategy
{

    /**
     * Stores $addedRouteArray matched with tokens and controllers
     *
     * @var array
     */
    private $matchedRouteArray = array();

    /**
     * Counter used to name routes
     *
     * @var integer
     */
    private static $countRoute = 0;

    /**
     * {@inheritDoc}
     * @see \Ignaszak\Router\Parser\ParserStrategy::run()
     */
    public function run()
    {
        $this->matchRouteWithToken();
        $this->matchPatternWithQueryString();
    }

    /**
     * Replaces tokens defined in route pattern with token pattern and adds
     * replaced route array to $matchedRouteArray
     */
    private function matchRouteWithToken()
    {
        foreach ($this->_routeController->getProperty('addedRouteArray') as $addedRoute) {

            $patternArray = explode('/', $addedRoute['pattern']);
            $returnArray = array();

            foreach ($patternArray as $pattern) {
                $patternString = $this->addParenthesisToString($pattern);
                $patternString = $this->addNameToPatternWithDefinedToken($patternString);
                $patternString = $this->addNameToPattern($patternString);
                $returnArray[] = $patternString;
            }

            self::$countRoute = 0;
            $patternString = implode('/', $returnArray);
            
            $pattern = str_replace(
                $this->_routeController->getProperty('tokenNameArray'),
                $this->_routeController->getProperty('tokenPatternArray'),
                $patternString
            );

            $this->addMatchedRoute(
                $addedRoute['name'],
                $pattern,
                $addedRoute['controller']
            );

        }
    }

    /**
     * Adds matched rout array
     *
     * @param string $name
     * @param string $pattern
     * @param string $controller
     * @param array $key
     */
    private function addMatchedRoute($name, $pattern, $controller = null, array $key = null)
    {
        $routeArray = $this->_routeController->createRouteArray($name, $pattern, $controller, $key);
        $this->matchedRouteArray[] = $routeArray;
    }

    /**
     * Adds parenthesis if pattern is alphabetic
     *
     * @param string $pattern
     * @return array
     */
    private function addParenthesisToString($pattern)
    {
        return ctype_alpha($pattern) ? "($pattern)" : $pattern;
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function addNameToPatternWithDefinedToken($pattern)
    {
        $replacement = array();
        $tokenNameArray = $this->_routeController->getProperty('tokenNameArray');
        $tokenPatternArray = $this->_routeController->getProperty('tokenPatternArray');
        $count = count($tokenPatternArray);

        for ($i=0; $i<$count; ++$i) {
            $name = $this->removeBraces($tokenNameArray[$i]);
            $replacement[] = "(?P<$name>{$tokenPatternArray[$i]})";
        }

        return str_replace($tokenNameArray, $replacement, $pattern);
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function addNameToPattern($pattern)
    {
        $patternArray = explode(':', $this->removeBraces($pattern));

        if (count($patternArray) == 2) {

            return "(?P<{$patternArray[0]}>{$patternArray[1]})";

        } elseif (strpos($patternArray[0], '?P<') === false) {

            ++ self::$countRoute;
            return "(?P<route" . self::$countRoute . ">{$patternArray[0]})";

        } else {
            return $patternArray[0];
        }
    }

    /**
     * @param string $pattern
     * @return string
     */
    private function removeBraces($pattern)
    {
        return str_replace(array('{','}'), '', $pattern);
    }

    /**
     * Matchs $matchedRouteArray with current query and adds result to $currentQueryArray
     */
    private function matchPatternWithQueryString()
    {
        $count = 0;

        foreach ($this->matchedRouteArray as $matchedRoute) {

            $pattern = $this->preparePatternToPregMatchFunction($matchedRoute['pattern']);
            $matchesArray = array();

            if (@preg_match($pattern, Conf::getQueryString(), $matchesArray) && !$count) {

                ++ $count;

                $headerArray = array(
                    'name' => $matchedRoute['name'],
                    'controller' => $matchedRoute['controller']
                );

                $keys = array_filter(array_keys($matchesArray), 'is_numeric');
                $currentQueryArray = array_diff_key($matchesArray, array_flip($keys)); // Remove integer keys
                $currentQueryArray = array_merge($headerArray, $currentQueryArray);
                $currentQueryArray = array_filter($currentQueryArray); // Remove empty elements
                self::$currentQueryArray = $currentQueryArray;
            }
        }
    }

    /**
     * Prepares patterns to preg_match function
     *
     * @param string $pattern
     * @return string
     */
    private function preparePatternToPregMatchFunction($pattern)
    {
        return "/^" . str_replace("/", "\\/", $pattern) . "$/";
    }
}
