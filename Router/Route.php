<?php
namespace Pat\Router;

use \Exception;

class Route
{
    /**
     * String specifing the route path
     *
     * @var string
     */
    protected $path;
    /**
     * Name of method of $controller which will be called by this route => $controller::$method
     *
     * @var string
     */
    protected $method;
    /**
     * Controller which will be used by this route
     *
     * @var string
     */
    protected $controller;
    /**
     * Array of regex'es for $path params
     * eg. $path = "/index/{page}" 
     *     $params = ["page" => "/\d+/"]
     *
     * @var array|null
     */
    protected $params;

    function __construct(string $path, string $controller, string $method, ?array $params = null)
    {
        $this->path = $path;
        $this->method = $method;
        $this->controller = $controller;
        $this->params = $params;
    }
    /**
     * Returns path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    /**
     * Returns Controller name
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }
    /**
     * Returns Method name
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    /**
     * Returns array containing param regexes | null
     *
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }
    /**
     * Generates Url from provided data array
     * Data array MUST contain ALL params specified for the route
     *
     * @param array $data
     * @return string
     */
    public function generateUrl(array $data = []): string
    {
        $url = $this->path;
        foreach($data as $key => $value)
        {
            if (!isset($this->params[$key]))
            { 
                throw new Exception(sprintf("Provided data for unknown key %s !", $key));
            }
            if (preg_match($this->params[$key], $value))
            {
                $url = str_replace("{".$key."}", $value, $url, 1);
            }
            else 
            {
                throw new Exception(sprintf("Provided data for key %s doesn't match regex %s !", $key. $this->params[$key]));
            }
        } 
        if(preg_match("/{[a-z]+}/i", $url, $insufficentKeys))
        { 
            throw new Exception(sprintf("Data not provided for keys %s !", implode(", ", $insufficentKeys)));
        }
        return $url;
    }
}
?>