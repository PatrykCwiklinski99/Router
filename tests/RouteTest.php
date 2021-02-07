<?php
namespace Pat\Router\Tests;

require_once __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Pat\Router\Route;

use \Exception;

class RouteTest extends TestCase
{

    public function testGeneratingSimpleUrl()
    {
        $route = new Route("test", "Control", "index");
        $link = $route->generateUrl();
        $this->assertEquals(
            "test",
            $link
        );
    }
    public function testGeneratingUrlWithParam()
    {
        $route = new Route("/test/{param}", "Control", "index", ["param" => "/[a-z]+/i"]);
        $link = $route->generateUrl(["param" => "abecadło"]);
        $this->assertEquals(
            "/test/abecadło",
            $link,
        );
    }

    public function testGeneratingErrorWithParam()
    {
        $this->expectException(Exception::class);
        $route = new Route("/test/{param}/{aram}", "Control", "index", ["param" => "/[a-z]+/i", "aram" => "/[a-z]+/i"]);
        $link = $route->generateUrl(["param" => "abecadło"]);
    }


}