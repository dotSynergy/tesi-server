<?php

namespace App\Controllers;

use Core\Template;

class AbstractController
{
    private $template;
 
    public function __construct()
    {
        $this->template = new Template();
    }
 
    protected function getView($controller, array $variables = [])
    {
        return $this->template->getView($controller, $variables);
    }

}