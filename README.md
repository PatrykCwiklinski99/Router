# Simple Router

A simple php script for routing.
Allows for use of parameterized paths.

## Table of Contents

- [Getting started](#getting-started)
  - [Notes](#notes-1)
  - [Requirements](#requirements)
  - [Example .htaccess](#htaccess)
  - [Creating first Route](#creating-first-route)
- [Simple Documentation](#simple-documentation)
  - [Router](#router)
  - [Routes](#routes)
  - [Route](#route)

### htaccess

Create a new `.htaccess` file in your projects `public` directory and paste the contents below in your newly created file. This will redirect all requests to your `index.php` file

```
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ public/index.php [NC,L]
```

### Creating first Route

Create a file named routes.php in your project library.
Start by including following line in it

```php
use Pat\Router\{
    Route,
    Routes
};

```

Remember to include it in your index.php file after autoload.php.

To create first GET route just type

```php
Routes::addRoute("GET", "Index", new Route("/", ExampleController::class, "index"));
```

To start Routing in your index.php file type in following line

```php
use Pat\Router\Router;

$router = new Router($_SERVER["REQUEST_URI"], $_SERVER["REQUEST_METHOD"]);
$router->run();
$route = $router->getRoute();

$controller = $route->getController();
$method = $route->getMethod();
$controller = new $controller();
$controller->$method();

```

### Creating Route with parameters

Parameters are added by wrapping a segment of path with brackets {}

eg.

```
$path = "/index/{page}"
```

And now providing a matching key to the optional 4th Route constructor parameter - array $params
and a regex as value.

Example - a GET Route with parameter {page}

```php
Routes::addRoute("GET", "Index@page", new Route("/index/{page}", IndexController::class, "page", ["page" => "/\d+/"));
```

Now to access the matched data just call Router::getData() after matching the route

```php
$data = $router->getRouteData();
```

This returns an associative array containing values from the link

## Simple Documentation

### Router

```php
    public function __construct(string $url, string $method);
```

$url - Request URL to match the route to
$method - Request Method

```php
    public function getRoute(): ?Route;
```

Returns matched Route OR null if no Route matched specified URL

```php
    public function getRouteData(): ?Route;
```

Returns associative Array containing Route::$param keys , and $values from url
OR null if no Route matched provided URL

```php
    public function run();
```

This function starts routing engine with $url and $method specified in constructor

### Routes

```php
    public static function addRoute(string $method, string $alias, Route $route);
```

$method - Request method which this Route will handle
$alias - Identifier for the Route with which you will then be able to retrieve it with

```php
Routes::getRoute($method, $alias)
```

While there aren't any Fixed rules for naming it
I would recommend naming it as route's path with / replaced with @ and {} ommitted
eg.

```php
    Routes::addRoute("GET", "index@page", new Route("/index/{page}", IndexController::class, "page", ["page" => "/\d+/"]););
```

```php
    public static function getRoute(string $method, string $alias): Route
```

Returns Route for specified Request method which was registered with $alias

```php
    public static function getAllRoutes(string $method): array;
```

Returns array containing Routes for specified Request method;

### Route

```php
    function __construct(string $path, string $controller, string $method, ?array $params = null);
```

$path <- Path on which the route will be matched
$controller <- Namespaced name of the class which router will use
$method <- Method of the controller object which you will call
optional $params <- associative array of {param} as key and regex as value

```php
    public function generateUrl(array $data = []): string
```

Generates a link to given route with specifed $data array.
That array MUST contain all $param keys specified in $params array
AND its values MUST match with corresponding regex'es
