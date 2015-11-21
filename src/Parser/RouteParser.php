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

            $patternString = $this->addParenthesisToString($addedRoute['pattern']);

            $pattern = str_replace(
                $this->_routeController->getProperty('tokenNameArray'),
                $this->_routeController->getProperty('tokenPatternArray'),
                $patternString
            );

            $this->addMatchedRoute(
                $addedRoute['name'],
                $pattern,
                $this->addControllerArray($addedRoute['controller']),
                $this->getTokenKeyArray($patternString)
            );

        }
    }

    /**
     * Matchs $matchedRouteArray with current query and adds result to $currentQueryArray
     */
    private function matchPatternWithQueryString()
    {
        $currentQueryArray = array();
        $count = 0;

        foreach ($this->matchedRouteArray as $matchedRoute) {

            $pattern = $this->preparePatternToPregMatchFunction($matchedRoute['pattern']);

            $matchesArray = array();

            if (@preg_match($pattern, Conf::getQueryString(), $matchesArray) && !$count) {

                ++ $count;
                $currentQueryArray['name'] = $matchedRoute['name'];

                if (!empty($matchedRoute['controller']))
                    $currentQueryArray['controller'] = $matchedRoute['controller'];

                foreach ($matchedRoute['key'] as $key => $keyName) {

                    if (!empty($matchesArray[$key + 1]))
                        $currentQueryArray[$keyName] = $matchesArray[$key + 1];

                }

                self::$currentQueryArray = $currentQueryArray;

            }
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
     * Creates and returns kays as subpatterns name
     * 
     * @param string $pattern
     * @return array
     */
    private function getTokenKeyArray($pattern)
    {
        $unamtchedTokenArray = explode('/', $pattern);
        $tokenKeyArray = array();
        $count = 1;

        foreach($unamtchedTokenArray as $unmatchedToken) {

            $tokenKeyArray[] = preg_replace(
                array('/((\(.*)\))/', '/\{/', '/\}/'),
                array("route$count", '', ''),
                $unmatchedToken
            );

            ++$count;

        }

        return $tokenKeyArray;
    }

    /**
     * Adds parenthesis if pattern is alphabetic
     * 
     * @param string $pattern
     * @return array
     */
    private function addParenthesisToString($pattern)
    {
        $patternArray = explode('/', $pattern);
        $returnArray = array();

        foreach ($patternArray as $value) {
            if (ctype_alpha($value)) {
                $returnArray[] = "($value)";
            } else {
                $returnArray[] = $value;
            }
        }

        return implode('/', $returnArray);
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

    /**
     * If route has controller returns controller array
     * 
     * @param string $controllerName
     * @return string
     */
    private function addControllerArray($controllerName)
    {
        $controllerArray = $this->_routeController->getProperty('controllerArray');

        if (array_key_exists($controllerName, $controllerArray))
            return $controllerArray[$controllerName];
    }

}
