<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\CharacterModel;
use Raith\Model\CharacterRaceModel;
use Raith\Model\CharacterAlignmentModel;
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
                return ucfirst($race->name);
            }, CharacterRaceModel::all()),
            'character_alignments' => array_map(function($alignment){
                return ucfirst($alignment->name);
            }, CharacterAlignmentModel::all())
        ];

        $html = new Html('Character/Create');
        $form = new Form('character_form', 'Form/Character/Create', null, true, $character_data);

        if(!empty($_POST) && $form->valid($_POST)){
            var_dump($form->values()); //TODO: check and insert
        }

        $html
            ->set('character_form', $form)
            ->sets($character_data)
            ->run();
    }
}