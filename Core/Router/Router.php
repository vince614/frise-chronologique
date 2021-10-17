<?php
namespace Core\Router;

use Core\Acl\ACL;
use Core\Controllers\ErrorController;

/**
 * Class Router
 * @package Router
 */
class Router
{

    /**
     * Url
     * @var string
     */
    private $url;

    /**
     * List of routes
     * @var array
     */
    private $routes = [];

    /**
     * Names of routes
     * @var array
     */
    private $namedRoutes = [];

    /**
     * Router constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Add route with GET_METHOD
     * @param $path
     * @param $callable
     * @param $acl
     * @param null $name
     * @return Route
     */
    public function get($path, $acl = ACL::EVERYONE, $callable = null, $name = null)
    {
        return $this->add($path, $callable, $acl, $name, 'GET');
    }

    /**
     * Add new route
     * @param $path
     * @param $callable
     * @param $acl
     * @param $name
     * @param $method
     * @return Route
     */
    private function add($path, $callable, $acl, $name, $method)
    {
        $route = new Route($path, $callable, $acl);
        $this->routes[$method][] = $route;
        if (is_string($callable) && $name === null) {
            $name = $callable;
        }
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /**
     * Add route with POST_METHOD
     * @param $path
     * @param $callable
     * @param int $acl
     * @param null $name
     * @return Route
     */
    public function post($path, $acl = ACL::EVERYONE, $callable = null, $name = null)
    {
        return $this->add($path, $callable, $acl, $name, 'POST');
    }

    /**
     * Run route
     * @return mixed
     * @throws RouterException
     */
    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }
        return new ErrorController('Errors.404');
    }

    /**
     * Url
     * @param $name
     * @param array $params
     * @return mixed
     * @throws RouterException
     */
    public function url($name, $params = [])
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}