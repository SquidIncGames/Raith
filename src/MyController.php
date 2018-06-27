<?php

namespace Raith;

use Krutush\Controller;
use Krutush\Template\Html;
use Raith\Model\Custom\SessionModel;

class MyController extends Controller{
    function getHtml(string $path, string $extention = null, bool $folder = true){
        return (new Html($path, $extention, $folder))
            ->set('router', $this->app->getRouter())
            ->set('isLogged', SessionModel::isLogged());
    }
}