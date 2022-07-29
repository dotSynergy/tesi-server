<?php

namespace App\Controllers;
 
use Core\Template;
use App\DB\ModelManager;
use App\Models\SensorsData;
 
class AssetsController extends AbstractController
{

    public function __construct()
    {
        parent::__construct(new Template());
    }

    public function jsMethod($urlParts)
    {
        echo file_get_contents(realpath($_ENV['APP_ROOT'].'/'.implode('/',$urlParts)));
    }

    public function imgMethod($urlParts)
    {
        echo file_get_contents(realpath($_ENV['APP_ROOT'].'/'.implode('/',$urlParts)));
    }
 
}