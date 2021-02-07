<?php
namespace Pat\Router\Tests;

require_once __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Pat\Router\{
    Route,
    Routes,
    Router
};
use \Exception;

class RouterTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        Routes::addRoute("GET", "test@with@multiple", new Route("/test/{with}/{multiple}/", "Controle", "hokus", ["with" => "/[a-z]+/i", "multiple" => "/[0-9]+/"]));
        Routes::addRoute("GET", "test@values", new Route("/test/{values}", "Controle", "hokus", ["values" => "/[a-z]+/i"]));
        Routes::addRoute("GET", "test", new Route("/test", "Test", "Tested"));
    }

    public function testSimpleRouting()
    {
        $router = new Router("/test", "GET", self::$routes);
        $router->run();
        $this->assertInstanceOf(
            Route::class,
            $router->getRoute()
        );
    }

    public function testRoutingWithParam()
    {
        $router = new Router("/test/abcds", "GET", self::$routes);
        $router->run();
        $this->assertInstanceOf(
            Route::class,
            $router->getRoute()
        );
    }

    public function testWithMultipleParams()
    {
        $router = new Router("/test/dada/132/", "GET", self::$routes);
        $router->run();
        $this->assertInstanceOf(
            Route::class,
            $router->getRoute()
        );
    }

}