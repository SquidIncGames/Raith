<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Raith\Template\Json;
use Krutush\Form\Form;

use Raith\Model\Custom\RandomModel;
use Raith\Model\World\ElementModel;

class RandomController extends MyController{
    public function index(){
        (new Html('Random/Index'))->run();
    }

    public function action(){
        $html = new Html('Random/Action');

        $random_data = [
            'stats' => array_map(function($element){
                return ['value' => $element->id, 'text' => ucfirst($element->getStat()->name), 'more' => '']; //TODO: Too many queries (preload stat)
            }, ElementModel::all())
        ];

        $form = new Form('action_form', 'Form/Random/Action', null, true, $random_data);
        $html->set('action_form', $form)
            ->sets($random_data);

        if(!empty($_POST) && $form->valid($_POST)){
            $values = $form->values();
            $statistique = [ //TODO: load from db
                7 => 49, //force
                8 => 30, //dext
                9 => 6, //int
                10 => 10, //sag
                11 => 12, //cha
                12 => 40 //const
            ][$values['statistique']];
            $html->set('statistique_name', $values['statistique'])
                ->set('statistique_value', $statistique);

            $count = intval($values['count']);
            if($count == 1){
                $html->sets(RandomModel::action($statistique));
            }else{
                $values = [];
                for($i = 0; $i < $count; $i++){
                    $values[] = RandomModel::action($statistique);
                }
                $html->set('values', $values);
            }
        }

        $html->run();
    }


    //API
    public function api_random(int $min, int $max){
        if($min > $max){
            Json::error('min must be less than max');
        }else{
            Json::run('random', [
                'min' => $min,
                'max' => $max,
                'value' => RandomModel::random($min, $max)
            ]);
        }
    }

    public function api_randoms(int $count, int $min, int $max){
        if($count <= 0){
            Json::error('count must be positive');
        }else if($min > $max){
            Json::error('min must be less than max');
        }else{
            $values = array();
            for($i = 0; $i < $count; $i++)
                $values[$i] = RandomModel::random($min, $max);
            Json::run('randoms', [
                'count' => $count,
                'min' => $min,
                'max' => $max,
                'values' => $values
            ]);
        }
    }

    public function api_dice(int $max){
        Json::run('dice', [
            'max' => $max,
            'value' => RandomModel::random(1, $max)
        ]);
    }

    public function api_dices(int $count, int $max){
        if($count <= 0){
            Json::error('count must be positive');
        }else{
            $values = array();
            for($i = 0; $i < $count; $i++)
                $values[$i] = RandomModel::random(1, $max);
            Json::run('dices', [
                'count' => $count,
                'max' => $max,
                'values' => $values
            ]);
        }
    }

    public function api_action(int $statistique){
        Json::run('action', ['statistique' => $statistique] + RandomModel::action($statistique));
    }

    public function api_actions(int $count, int $statistique){
        if($count <= 0){
            Json::error('count must be positive');
        }else{
            $values = array();
            for($i = 0; $i < $count; $i++)
                $values[$i] = RandomModel::action($statistique);
            Json::run('actions', [
                'count' => $count,
                'statistique' => $statistique,
                'values' => $values
            ]);
        }
    }
}