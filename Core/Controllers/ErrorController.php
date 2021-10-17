<?php
namespace Core\Controllers;

use Core\Controllers\Controller;

/**
 * Class ErrorController
 * @package Controllers
 */
class ErrorController extends Controller
{
    protected function afterRender()
    {
        $this->forbidden();
    }
}