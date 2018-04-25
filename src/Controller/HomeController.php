<?php

namespace Raith\Controller;

use Krutush\Controller;
use Krutush\Template\Html;

class HomeController extends Controller{
    public function index(){
        (new Html('Index'))->run();
    }
}