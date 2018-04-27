<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Raith\Model\RandomModel;

class RandomController extends MyController{
    public function menu(){
        echo "dice";
    }

    public function random($min, $max){
        (new Html('Random/Random'))
            ->set('rand_min', $min)
            ->set('rand_max', $max)
            ->set('rand_value', RandomModel::random($min, $max))
            ->run();
    }

    public function dice($max){
        $this->random(1, $max);
    }

    public function action($statistique){
        (new Html('Random/Action'))
            ->sets(RandomModel::action($statistique))
            ->run();
    }
}