<?php
namespace Core\Utils;

use Core\Controllers\ErrorController;

/**
 * Class Render
 * @package Core\Utils
 */
class Render
{

    /**
     * Error 404 path
     */
    const ERROR_404_PATH = 'Errors.404';

    /**
     * @var string
     */
    protected $view;

    /**
     * @var mixed
     */
    protected $vars;

    public function __construct($view, $vars)
    {
        $this->view = $view;
        $this->vars = $vars;
        $this->render();
    }

    /**
     * Render
     *
     * @return ErrorController|mixed
     */
    public function render()
    {
        extract($this->vars);
        $viewFile = ROOT . '/App/Views/' . str_replace('.', '/', $this->view) . '.phtml';
        return is_file($viewFile) ?
            require $viewFile :
            new ErrorController(self::ERROR_404_PATH);
    }

}