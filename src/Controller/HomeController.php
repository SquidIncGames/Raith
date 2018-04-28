<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;

class HomeController extends MyController{
    public function index(){
        (new Html('Index'))->run();
    }

    public function notfound($url){
        Json::error('not_found', 404);
    }
}