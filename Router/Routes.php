<?php
namespace Pat\Router;

use \Exception;

class Routes
{
    protected static $GET = [];
    protected static $POST = [];
    protected static $PUT = [];
    protected static $DELETE = [];
    
    /**
     * Adds new Route to specified $method array
     *
     * @param string $method
     * @param string $alias
     * @param Route $route
     * @throws Exception - when trying to access unimplemented request method
     * @throws Exception - when trying to 
     */
    public static function addRoute(string $method, string $alias, Route $route)
    {
        $method = \strtoupper($method);
        if (!isset(self::$$method))
        {
            throw new Exception("Method $method isn't available!");
        } 
        elseif (isset(self::$$method[$alias])) 
        {
            throw new Exception("Route with alias $alias for method $method was already set!");
        }
        self::$$method[$alias] = $route;
    }

    /**
     * Returns Route with provided $alias
     *
     * @param string $method
     * @param string $alias
     * @return Route
     * @throws Exception if there isn't a route with provided $alias in $method array
     */
    public static function getRoute(string $method, string $alias): Route
    {
        if (!isset(self::$$method[$alias]))
        {
            throw new Exception(\sprintf("Route %s not found in %s method routes!", $alias, $method));
        }
        return self::$$method[$alias];
    }

    /**
     * Returns all Routes in $method array
     *
     * @param string $method
     * @return array
     */
    public static function getAllRoutes(string $method): array
    {
        if (!isset(self::$$method))
        {
            throw new Exception("Method $method isn't available!");
        }
        return self::$$method;
    }
}