<?php

namespace Ignaszak\Router;

class RouteParser extends Router
{

    public function matchRouteWithToken()
    {
        foreach (parent::$addedRouteArray as $addedRoute) {

            $pattern = str_replace(
                parent::$tokenNameArray,
                parent::$tokenPatternArray,
                $addedRoute['pattern']
            );

            parent::addMatchedRoute(
                $addedRoute['name'],
                $pattern,
                $addedRoute['controller'],
                $this->getTokenKeyArray($addedRoute['pattern'])
            );

        }
    }

    public function matchPatternWithQueryString()
    {
        $currentQueryArray = array();
        $count = 0;

        foreach (parent::$matchedRouteArray as $matchedRoute) {

            $pattern = $this->addParenthesisToString($matchedRoute['pattern']);
            $pattern = $this->preparePatternToPregMatchFunction($pattern);

            $matchesArray = array();

            if (@preg_match($pattern, Conf::getQueryString(), $matchesArray) && !$count) {

                ++ $count;
                $currentQueryArray['name'] = $matchedRoute['name'];
                $currentQueryArray['controller'] = $matchedRoute['controller'];

                foreach ($matchedRoute['key'] as $key => $keyName) {
                    $currentQueryArray[$keyName] = @$matchesArray[$key + 1];
                }

                parent::$currentQueryArray = $currentQueryArray;
            }
        }
    }

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

    private function preparePatternToPregMatchFunction($pattern)
    {
        return "/^" . str_replace("/", "\\/", $pattern) . "$/";
    }

}
