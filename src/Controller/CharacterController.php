<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Form\Form;
use Krutush\HttpException;
use Krutush\Template\StringFormat;

use Raith\Model\SettingModel;
use Raith\Model\Action\StatModificationModel;
use Raith\Model\Character\CharacterModel;
use Raith\Model\Character\CharacterRaceModel;
use Raith\Model\Character\CharacterAlignmentModel;
use Raith\Model\User\UserCharacterRightModel;
use Raith\Model\World\WeaponTypeModel;
use Raith\Model\World\JobModel;
use Raith\Model\Custom\SessionModel;

class CharacterController extends MyController{
    public static function getCharacter($user, $app, string $rightType = null): CharacterModel{
        if(SessionModel::haveCharacterId()){
            $character = CharacterModel::find(SessionModel::getCharacterId());
            if($character != null && $character->valid){
                if($character->owner == $user->id)
                    return $character;

                if($rightType != null){
                    foreach($character->_rights as $right){
                        if($right->user == $user->id && $right->$rightType)
                            return $character;
                    }
                }
            }
        }

        $router = $app->getRouter(); //TODO: redirect link page
        $router->redirect($router->get('characters')->getUrl());
    }

    public function index(){
        $user = UserController::checkLogged($this->app);
        $characters = $user->getCharacters('canPlay');

        $html = $this->getHtml('Character/Index');

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
        if(!empty($user->_characters)){
            $router = $this->app->getRouter();
            $router->redirect($router->get('characters')->getUrl());
        }

        $character_data = [
            'character_races' => array_map(function($race){
                return ['value' => $race->name, 'text' => StringFormat::ucfirst($race->name), 'more' => ''];
            }, CharacterRaceModel::all()),
            'character_alignments' => array_map(function($alignment){
                return ['value' => $alignment->id, 'text' => StringFormat::ucfirst($alignment->name), 'more' => ''];
            }, CharacterAlignmentModel::all()),
            'weapon_types' => array_map(function($weapon){
                return ['id' => $weapon->id, 'value' => 'weapon-'.$weapon->id, 'text' => StringFormat::ucfirst($weapon->_id->name), 'more' => ''];
            }, WeaponTypeModel::load(WeaponTypeModel::all(), 'id')),
            'jobs' => array_map(function($job){
                return ['id' => $job->id, 'value' => 'job-'.$job->id, 'text' => StringFormat::ucfirst($job->_id->name), 'more' => ''];
            }, JobModel::load(JobModel::all(), 'id'))
        ];

        $character_settings = SettingModel::values([
            'character_create_max_stat_points',
            'character_create_max_job_points',
            'character_create_max_weapon_points',
            'character_create_place'
        ]);

        $html = $this->getHtml('Character/Create');
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
            if($weaponPoints > $character_settings['character_create_max_weapon_points']){
                $form->error('Pas assez de points de maitrise d\'arme');
            }else{
                $jobPoints = 0;
                foreach($character_data['jobs'] as $job){
                    $value = intval($values[$job['value']]);
                    $jobPoints += $value;
                    if($value > 0)
                        $maitrises[$job['id']] = $value;
                }
                if($jobPoints > $character_settings['character_create_max_job_points']){
                    $form->error('Pas assez de points de maitrise de metier');
                }else{
                    if($weaponPoints + $jobPoints > $character_settings['character_create_max_stat_points']){
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
                            'place' => $character_settings['character_create_place'],
                            'owner' => $user->id
                        ]);

                        try {
                            $newCharacter->runInsert();
                            StatModificationModel::insertModification($user->id, $newCharacter->id, $newCharacter->place, new \DateTime(), false, 'création des maitrises', $maitrises);
                            //MAYBE: discord ?
                            $this->getHtml('Character/Created')->run();
                            return;
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
            ->sets($character_settings)
            ->run();
    }

    public function infos(int $id){
        $user = UserController::checkLogged($this->app);

        $character = CharacterModel::find($id);
        if($character == null)
            throw new HttpException(404);

        UserCharacterRightModel::load($character->_rights, 'user');

        /*if(in_array($character, $user->_characters)){
            $this->getHtml('Character/Details'))
                ->set('character', $character)
                ->run();
        }else{*/
            $this->getHtml('Character/Infos')
                ->set('character', $character)
                ->run();
        //}
    }
}