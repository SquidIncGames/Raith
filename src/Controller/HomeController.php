<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;

class HomeController extends MyController{
    public function index(){
        $this->getHtml('Index')->run();
    }

    public function notfound($url){
        Json::error('not_found', 404);
    }
}