<?php

namespace Ignaszak\Router\Parser;

use Ignaszak\Router\Conf;

/**
 * 
 * @author Tomasz Ignaszak <tomek.ignaszak@gmail.com>
 * @link https://github.com/ignaszak/router/blob/master/src/Parser/RouteParser.php
 *
 */
class RouteParser extends ParserStrategy
{

    /**
     * @var array
     */
    private $matchedRouteArray = array();

    public function run()
    {
        $this->matchRouteWithToken();
        $this->matchPatternWithQueryString();
    }

    /**
     * @param string $name
     * @param string $pattern
     * @param string $controller
     * @param array $key
     */
    private function addMatchedRoute($name, $pattern, $controller = null, array $key = null)
    {
        $routeArray = $this->_routeController->createRouteArray($name, $pattern, $controller, $key);
        $this->matchedRouteArray = array_merge($this->matchedRouteArray, array($routeArray));
    }

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
                $currentQueryArray['controller'] = $matchedRoute['controller'];

                foreach ($matchedRoute['key'] as $key => $keyName) {
                    $currentQueryArray[$keyName] = @$matchesArray[$key + 1];
                }

                self::$currentQueryArray = $currentQueryArray;

            }
        }
    }

    /**
     * @param string $pattern
     * @return array
     */
    private function getTokenKeyArray($pattern)
    {
        $unamtchedTokenArray = explode('/', $pattern);
        $tokenKeyArray = array();
        $count = 1;

        foreach($unamtchedTokenArray as $unmatchedToken) {

            $matchedTokenArray = preg_replace(
                array('/((\(.*)\))/', '/\{/', '/\}/'),
                array("route$count", '', ''),
                $unmatchedToken
            );

            $tokenKeyArray = array_merge($tokenKeyArray, array($matchedTokenArray));
            ++$count;

        }

        return $tokenKeyArray;
    }

    /**
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
     * @param string $pattern
     * @return string
     */
    private function preparePatternToPregMatchFunction($pattern)
    {
        return "/^" . str_replace("/", "\\/", $pattern) . "$/";
    }

    /**
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
