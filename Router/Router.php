<?php

namespace Pat\Router;

class Router
{
    /**
     * $url - Request Url without fragment / query
     * @var string
     */
    private $url;
    /**
     * Request method
     *
     * @var string
     */
    private $method;
    /**
     * Data from url
     *
     * @var array|null
     */
    private $data;
    /**
     * Routes container
     *
     * @var Routes
     */
    private $routes = null;
    /**
     * Matched Route
     *
     * @var Route|null
     */
    private $route = null;

    public function __construct(?string $url = null, ?string $method = null)
    {
        if ($url === null){
            $url = $_SERVER["REQUEST_URI"];
            $url = explode("#", $url, 2)[0];
            $url = explode("?", $url, 2)[0];
            if (substr($url, -1) === "/")
            {
                $url = \substr($url, 0, -1);    
            }
            $url = \strtolower($url);
        }
        $this->url = $url;
        $this->method = $method ?? $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Returns data from Route params
     *
     * @return array|null
     */
    public function getRouteData(): ?array
    {
        return $this->data;
    }
    /**
     * Returns matched Route or null if no route matches the url
     *
     * @return Route|null
     */
    public function getRoute(): ?Route
    {
        return $this->route;
    }

    /**
     * Starts the routing process
     * if a matching Route is found sets @var $this->route and @var $this->data
     *
     * @return void
     */
    public function run()
    {
        foreach (Routes::getAllRoutes($this->method) as $route)
        {
            if (is_array($this->data = $this->matches($route))) {
                $this->route = $route;
                return $route;
                break;
            }
        } 
    }

    /**
     * Matches url against Routes
     *
     * @param Route $route - route to match url to
     * @return array|null - returns data from matching route or null if didn't match
     */
    private function matches(Route $route): ?array
    {
        $params = $route->getParams();
        $path = $route->getPath();
        $data = [];
        if (
            $params === null
            && str_replace("/", "", $this->url)
            === str_replace("/", "", $path)
        ){
            return $data;
        }
        $urlSegments = explode("/", $this->url);
        $routeSegments = explode("/", $path);
        for ($i = 0; $i < count($routeSegments); $i++)
        {
            if (isset($urlSegments[$i]))
            {
                if ($urlSegments[$i] === $routeSegments[$i])
                {
                    continue;
                } 
                elseif (preg_match("/{[a-z]+}/i", $routeSegments[$i], $key))
                {
                    $key = \str_replace(["{", "}"], "", $key[0]);
                    if (!isset($params[$key])) {
                        throw new Exception(sprintf("Regex for param %s not provided!"), $key);
                    }                   
                    if (preg_match($params[$key], $urlSegments[$i], $value))
                    {
                        $data[$key] = $value[0];
                        continue;
                    }
                }
            }
            return null;
        }
        return $data;
    } 
 
} 