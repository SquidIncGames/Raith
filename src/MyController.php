<?php

namespace Raith;

use Krutush\Controller;
use Krutush\Template\Html;

class MyController extends Controller{
    function getHtml(string $path, string $extention = null, bool $folder = true){
        return (new Html($path, $extention, $folder = true))->set('router', $this->app->getRouter());
    }
}