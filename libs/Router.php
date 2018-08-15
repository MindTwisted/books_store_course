<?php

namespace libs;

use \Exception;

class Router
{
    protected static $methods = ['GET', 'POST', 'PUT', 'DELETE'];
    protected static $routes = [];

    public static function getUrl($routeName, $params = [])
    {
        $url = self::$routes[$routeName]['url'];

        if (count($params)) 
        {
            foreach ($params as $key => $val) 
            {
                $urlWithParams = str_replace(":{$key}", $val, $url);
            }
        }

        return str_replace('//', '/', isset($urlWithParams) ? $urlWithParams : $url);
    }

    public static function add($routeName, $routeSettings)
    {
        $routeSettings['url'] = trim($routeSettings['url'], '/');

        self::$routes[$routeName] = $routeSettings;
    }

    public static function match($uri, $method)
    {
        $uri = trim($uri, '/');

        preg_match('/\/xml|\/txt|\/html|\/json/', $uri, $renderTypeMatch, PREG_OFFSET_CAPTURE);

        if (isset($renderTypeMatch[0]))
        {
            View::setRenderType(trim($renderTypeMatch[0][0], '/'));
            $uri = substr($uri, 0, $renderTypeMatch[0][1]);
        }

        foreach (self::$routes as $key => $val) 
        {
            $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($val['url'])) . "$@D";

            if ($val['method'] === $method
            && preg_match($pattern, $uri, $matches))
            {
                return [
                    'settings' => $val,
                    'param' => count($matches) > 1 ? $matches[1] : null,
                ];
            }
        }

        return View::render([
            'text' => "Current route doesn't exists."
        ], 404);
    }

    public static function currentRouteName()
    {
        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

        foreach (self::$routes as $key => $val) 
        {
            if ($uri === $val['url']) 
            {
                return $key;
            }
        }
    }
}