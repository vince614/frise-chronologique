<?php
namespace Core\Router;

use Core\Acl\ACL;

/**
 * Class Route
 * @package Router
 */
class Route
{

    /**
     * Path
     * @var string
     */
    private $path;

    /**
     * Callable
     * @var mixed
     */
    private $callable;

    /**
     * Matches
     * @var array
     */
    private $matches = [];

    /**
     * Params
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $acl = [];

    /**
     * Route constructor.
     * @param $path
     * @param $callable
     * @param $acl
     */
    public function __construct($path, $callable, $acl)
    {
        $this->path = trim($path, '/');  // On retire les / inutils
        $this->callable = $callable;
        $this->acl = $acl;
    }

    /**
     * Permettra de capturer l'url avec les paramètre
     * get('/posts/:slug-:id') par exemple
     * @param $url
     * @return bool
     */
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Call route
     * @return mixed
     */
    public function call()
    {
        $this->load();
        if ($this->callable) {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    private function formatePath()
    {
        // Index controller
        if ($this->path === "") $this->path = "index";
        $this->path = rtrim(explode(':', $this->path)[0], "/");
    }

    /**
     * Load controller
     */
    private function load()
    {
        // ACL
        $ACL = new ACL($this->acl);
        $ACL->run();

        // Formate path
        $this->formatePath();

        $controller = ucfirst($this->path);

        $controllerNamespace = "App\\Controllers\\";
        $controllerClass = $controller . "Controller";
        $controllerFile = ROOT . "/App/Controllers/" . $controllerClass . '.php';
        $controllerClassWithNamespace = $controllerNamespace . $controllerClass;

        // If controller exist
        if (file_exists($controllerFile)) {
            new $controllerClassWithNamespace($this->path, $this->matches);
        }
    }
}