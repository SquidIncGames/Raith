<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\Action\StatModificationModel;
use Raith\Model\Character\CharacterModel;
use Raith\Model\Character\CharacterRaceModel;
use Raith\Model\Character\CharacterAlignmentModel;
use Raith\Model\World\WeaponTypeModel;
use Raith\Model\World\JobModel;
use Raith\Model\Custom\SessionModel;

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

        //NOTE: Current limit at 1 character / user
        if(!empty($user->getCharacters())){
            $router = $this->app->getRouter();
            $router->redirect($router->get('characters')->getUrl());
        }

        $character_data = [
            'character_races' => array_map(function($race){
                return ['value' => $race->name, 'text' => ucfirst($race->name), 'more' => ''];
            }, CharacterRaceModel::all()),
            'character_alignments' => array_map(function($alignment){
                return ['value' => $alignment->id, 'text' => ucfirst($alignment->name), 'more' => ''];
            }, CharacterAlignmentModel::all()),
            'weapon_types' => array_map(function($weapon){
                return ['id' => $weapon->id, 'value' => 'weapon-'.$weapon->id, 'text' => ucfirst($weapon->_id->name), 'more' => '']; //TODO: Too many queries (preload stat)
            }, WeaponTypeModel::load(WeaponTypeModel::all(), 'id')),
            'jobs' => array_map(function($job){
                return ['id' => $job->id, 'value' => 'job-'.$job->id, 'text' => ucfirst($job->_id->name), 'more' => ''];
            }, JobModel::load(JobModel::all(), 'id'))
        ];

        $html = new Html('Character/Create');
        $form = new Form('character_form', 'Form/Character/Create', null, true, $character_data);

        if(!empty($_POST) && $form->valid($_POST)){
            $values = $form->values();
            $maitrises = [];
            $weaponPoints = 0;
            foreach($character_data['weapon_types'] as $weapon){
                $value = intval($values[$weapon['value']]);
                $weaponPoints += $value;
                if($value > 0)
                    $maitrises[$weapon['id']] = $value;
            }
            if($weaponPoints > 30){ //MAYBE: const ?
                $form->error('Pas assez de points de maitrise d\'arme');
            }else{
                $jobPoints = 0;
                foreach($character_data['jobs'] as $job){
                    $value = intval($values[$job['value']]);
                    $jobPoints += $value;
                    if($value > 0)
                        $maitrises[$job['id']] = $value;
                }
                if($jobPoints > 30){
                    $form->error('Pas assez de points de maitrise de metier');
                }else{
                    if($weaponPoints + $jobPoints > 45){
                        $form->error('Pas assez de points de maitrise');
                    }else{
                        $newCharacter = new CharacterModel([
                            'surname' => $values['nom'],
                            'firstname' => $values['prenom'],
                            'nickname' => $values['surnom'],
                            'race' => strtolower($values['race']),
                            'birthday' => $values['dateNaissance'],
                            'size' => $values['taille'],
                            'weight' => $values['poids'],
                            'alignment' => $values['alignement'],
                            'personality' => $values['personnalite'],
                            'description' => $values['descPhysique'],
                            'history' => $values['histoire'],
                            'owner' => $user->id
                        ]);

                        try {
                            $newCharacter->runInsert();
                            StatModificationModel::insertModification($user->id, $newCharacter->id, $newCharacter->place, new \DateTime(), false, 'création des maitrises', $maitrises);
                            //MAYBE: discord ?
                            (new Html('Character/Created'))->run();
                            return;
                            echo 'redirect';
                        } catch (\Exception $e) {
                            $form->error('Erreur inconnue durant la création');
                        }
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