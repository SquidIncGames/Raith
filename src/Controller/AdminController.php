<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;
use Krutush\Template\StringFormat;
use Krutush\HttpException;

use Raith\Model\User\UserModel;
use Raith\Model\Character\CharacterModel;
use Raith\Model\Action\ActionModel;
use Raith\Model\Action\StatModificationModel;
use Raith\Model\Action\StatModificationLineModel;
use Raith\Model\Action\RollModel;
use Raith\Model\Action\SuccessRollModel;
use Raith\Model\World\ElementModel;
use Raith\Model\Custom\SessionModel;
use Raith\Model\Custom\DiscordModel;

class AdminController extends MyController{
    public static function checkAdmin(): UserModel{
        if(SessionModel::isLogged()){
            $user = UserModel::find(SessionModel::getUserId());
            if($user != null){
                if($user->_role->isAdmin)
                    return $user;
            }
        }

        throw new HttpException(401, "Restricted Acces");
    }

    public function index(){
        static::checkAdmin();
        $this->getHtml('Admin/Index')->run();
    }

    public function validateUsers(){
        static::checkAdmin();

        $visitors = UserModel::allVisitors();
        $html = $this->getHtml('Admin/Validate/Users');

        if($visitors != null){
            if(!empty($_POST) && isset($_POST['user_id']) && ctype_digit($_POST['user_id']) && isset($_POST['action']) && in_array($_POST['action'], ['validate', 'delete'])){
                foreach ($visitors as $key => $visitor) {
                    if($visitor->id == $_POST['user_id']){
                        if($_POST['action'] == 'validate'){
                            $visitor->role = SettingModel::value('role_default');
                            $visitor->runUpdate();
                            DiscordModel::inscription('<@!'.$visitor->discord.'> ('.$visitor->name.') a été accepté sur le site !');
                        }else{
                            $visitor->runDelete();
                        }
                        unset($visitors[$key]);
                        break;
                    }
                }
            }
            if($visitors != null) $html->set('visitors', $visitors);
        }

        $html->run();
    }

    public function validateCharacters(){
        static::checkAdmin();

        $characters = CharacterModel::load(CharacterModel::allByValidity(false), 'owner');
        $html = $this->getHtml('Admin/Validate/Characters');

        if($characters != null){
            if(!empty($_POST) && isset($_POST['character_id']) && ctype_digit($_POST['character_id'])){
                foreach ($characters as $key => $character) {
                    if($character->id == $_POST['character_id']){
                        $character->runDelete();
                        unset($characters[$key]);
                        break;
                    }
                }
            }
            if($characters != null) $html->set('characters', $characters);
        }

        $html->run();
    }

    public function validateCharacter(int $id){
        static::checkAdmin();

        $character = CharacterModel::find($id);
        if($character == null || $character->valid)
            throw new HttpException(404);

        $character_data = [
            'elements' => array_map(function($element){
                return ['id' => $element->id, 'value' => 'element-'.$element->id, 'text' => StringFormat::ucfirst($element->_id->name), 'more' => ''];
            }, ElementModel::load(ElementModel::all(), 'id'))
        ];

        $html = $this->getHtml('Admin/Validate/Character');
        $form = new Form('validate_character_form', 'Form/Admin/Validate/Character', null, true, $character_data);

        if(!empty($_POST) && $form->valid($_POST)){
            $values = $form->values();
            $elements = [];
            foreach($character_data['elements'] as $element){
                $elements[$element['id']] = intval($values[$element['value']]);
            }
            try {
                StatModificationModel::insertModification($character->owner, $character->id, $character->place, new \DateTime(), true, 'création des elements', $elements);
                $character->valid = true;
                $character->runUpdate();
                $this->validateCharacters();
                return;
            } catch (\Exception $e) {
                $form->error('Erreur inconnue durant la validation');
            }
        }

        $html
            ->set('validate_character_form', $form)
            ->set('character', $character)
            ->sets($character_data)
            ->run();
    }

    public function validateActions(){
        static::checkAdmin();

        $actions = ActionModel::loads(ActionModel::allByValidity(false), [ //A bit every
            'user',
            'character',
            'message',
            'place',
            'roll' => [
                'dices',
                'custom',
                'damage',
                'success' => [
                    'elementType' => ['id']
                ]
            ],
            'stat_modification' => [
                'lines' => ['stat']
            ]
        ]);
        
        $html = $this->getHtml('Admin/Validate/Actions');

        if($actions != null){
            if(!empty($_POST) && isset($_POST['action_id']) && ctype_digit($_POST['action_id']) && isset($_POST['action']) && in_array($_POST['action'], ['validate', 'delete'])){
                foreach ($actions as $key => $action) {
                    if($action->id == $_POST['action_id']){
                        if($_POST['action'] == 'validate'){
                            $action->validate();
                        }else{
                            $action->runDelete();
                        }
                        unset($actions[$key]);
                        break;
                    }
                }
            }
            if($actions != null) $html->set('actions', $actions);
        }
        
        $html->run();
    }
}