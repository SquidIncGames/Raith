<?php

namespace Raith\Controller;

use Krutush\Controller;
use Krutush\Template\Html;
use \Krutush\Database\Connection;

class HomeController extends Controller{
    public function index(){
        var_dump(\Raith\Model\UtilisateurModel::connect("admin", 'banana'));

        (new Html('Index'))->run();
    }
}