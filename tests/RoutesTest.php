<?php
namespace Pat\Router\Tests;

require_once  __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Pat\Router\{
    Route,
    Routes,
};
use \Exception;

class RoutesTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        Routes::addRoute("GET", "test@with@multiple", new Route("/test/{with}/{multiple}/", "Controle", "hokus", ["with" => "/[a-z]+/i", "multiple" => "/[0-9]+/"]));
        Routes::addRoute("GET", "test@values", new Route("/test/{values}", "Controle", "hokus", ["sex" => "/[a-z]+/i"]));
        Routes::addRoute("GET", "test", new Route("test", "Test", "Tested"));
    }

    public function testRouteAddWrongMethod()
    {
        $this->expectException(Exception::class);
        Routes::addRoute("null", "test", new Route("", "", ""));
    }

    public function testRouteAddDuplicate()
    {
        $this->expectException(Exception::class);
        Routes::addRoute("GET", "test", new Route("", "", ""));
    }

    public function testGetRoute()
    {
        $this->assertInstanceOf(Route::class, Routes::getRoute("GET", "test"));
    }

}