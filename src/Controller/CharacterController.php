<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\CharacterModel;
use Raith\Model\CharacterRaceModel;
use Raith\Model\CharacterAlignmentModel;
use Raith\Model\WeaponTypeModel;
use Raith\Model\JobModel;
use Raith\Model\SessionModel;
use Raith\Model\DiscordModel;

class CharacterController extends MyController{
    public function index(){
        $user = UserController::checkLogged($this->app);
        $characters = $user->getCharacters();

        $html = (new Html('Character/Index'));

        if($characters != null){
            if(!empty($_POST) && isset($_POST['character_id']) && ctype_digit($_POST['character_id'])){
                foreach ($characters as $character) {
                    if($character->id == $_POST['character_id']){
                        SessionModel::setCharacterId($character->id);
                        break;
                    }
                }
            }
            $html
                ->set('current_character', SessionModel::getCharacterId())
                ->set('characters', $characters);
        }
        
        $html->run();
    }

    public function create(){
        $user = UserController::checkLogged($this->app);

        $character_data = [
            'character_races' => array_map(function($race){
                return ['value' => $race->name, 'text' => ucfirst($race->name), 'more' => ''];
            }, CharacterRaceModel::all()),
            'character_alignments' => array_map(function($alignment){
                return ['value' => $alignment->id, 'text' => ucfirst($alignment->name), 'more' => ''];
            }, CharacterAlignmentModel::all()),
            'weapon_types' => array_map(function($weapon){
                return ['value' => 'weapon-'.$weapon->id, 'text' => ucfirst($weapon->name), 'more' => ''];
            }, WeaponTypeModel::all()),
            'jobs' => array_map(function($job){
                return ['value' => 'job-'.$job->id, 'text' => ucfirst($job->name), 'more' => ''];
            }, JobModel::all())
        ];

        $html = new Html('Character/Create');
        $form = new Form('character_form', 'Form/Character/Create', null, true, $character_data);

        if(!empty($_POST) && $form->valid($_POST)){
            $values = $form->values();
            $weaponPoints = 0;
            foreach($character_data['weapon_types'] as $weapon){
                $weaponPoints += intval($values[$weapon['value']]);
            }
            if($weaponPoints > 30){ //MAYBE: const ?
                $form->error('Pas assez de points de maitrise d\'arme');
            }else{
                $jobPoints = 0;
                foreach($character_data['jobs'] as $job){
                    $jobPoints += intval($values[$job['value']]);
                }
                if($jobPoints > 30){
                    $form->error('Pas assez de points de maitrise de metier');
                }else{
                    if($weaponPoints + $jobPoints > 45){
                        $form->error('Pas assez de points de maitrise');
                    }else{
                        //TODO: Insert
                        var_dump($values);
                    }
                }
            }
        }

        $html
            ->set('character_form', $form)
            ->sets($character_data)
            ->run();
    }
}