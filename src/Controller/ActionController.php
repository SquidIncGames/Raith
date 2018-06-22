<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;
use Raith\Model\Custom\DiscordModel;
use Raith\Model\Action\ActionModel;
use Raith\Model\Action\MessageModel;
use Raith\Model\Action\CustomRollModel;
use Raith\Model\Action\SuccessRollModel;
use Raith\Model\Action\StatModificationModel;
use Raith\Model\Action\DamageRollModel;
use Raith\Model\World\ElementModel;
use Raith\Model\World\WeaponModel;

class ActionController extends MyController{
    public function index(){
        $user = UserController::checkLogged($this->app);
        $character = CharacterController::getCharacter($user, $this->app);
        $this->getHtml('Action/Index')
            ->set('character', $character)
            ->run();
    }

    public function message(){
        $user = UserController::checkLogged($this->app);
        $character = CharacterController::getCharacter($user, $this->app);

        $form = new Form('message_form', 'Form/Action/Message');
        if(!empty($_POST) && $form->valid($_POST)){
            $message = new MessageModel([
                'id' => ActionModel::insertAction($user->id, $character->id, $character->place, new \DateTime(), false)->id,
                'message' => $form->get('message')->get()
            ]);
            $message->runInsert(false);
            DiscordModel::historique($message->discordText());
            $this->messageDisplay($message);
            return;
        }

        $this->getHtml('Action/Message')
            ->set('message_form', $form)
            ->set('character', $character)
            ->run();
    }

    private function messageDisplay($message){
        $this->getHtml('Action/MessageDisplay')
            ->set('message', $message)
            ->run();
    }

    public function customRoll(){
        $user = UserController::checkLogged($this->app);
        $character = CharacterController::getCharacter($user, $this->app);

        $form = new Form('custom_form', 'Form/Action/Roll/Custom');
        if(!empty($_POST) && $form->valid($_POST)){
            $custom = CustomRollModel::makeCustomRoll(
                $user->id, $character->id, $character->place,
                $form->get('description')->get(),
                $form->get('roll')->get(),
                $form->get('count')->get()
            );
            DiscordModel::historique($custom->discordText());
            $this->customRollDisplay($custom);
            return;
        }

        $this->getHtml('Action/Roll/Custom')
            ->set('custom_form', $form)
            ->set('character', $character)
            ->run();
    }

    private function customRollDisplay($custom){
        $this->getHtml('Action/Roll/CustomDisplay')
            ->set('custom', $custom)
            ->run();
    }

    public function successRoll(){
        $user = UserController::checkLogged($this->app);
        $character = CharacterController::getCharacter($user, $this->app);

        $stats = StatModificationModel::getCharacterStats($character->id);

        $success_data = [
            'elements' => array_map(function($element){
                return ['id' => $element->id, 'value' => $element->id, 'text' => ucfirst($element->_id->name), 'more' => ''];
            }, ElementModel::load(ElementModel::all(), 'id')),
            'weapons' => array_map(function($weapon){
                return ['id' => $weapon->id, 'value' => $weapon->id, 'text' => ucfirst($weapon->_type->_id->name), 'more' => ''];
            }, WeaponModel::loads(WeaponModel::all(), ['type' => ['id']])) //TODO: list character weapons
        ];

        $form = new Form('success_form', 'Form/Action/Roll/Success', null, true, $success_data);
        if(!empty($_POST) && $form->valid($_POST)){
            $element = $form->get('element')->get();
            $success = SuccessRollModel::makeSuccessRoll(
                $user->id, $character->id, $character->place,
                $form->get('description')->get(),
                $form->get('considered')->get() ?: false,
                $element,
                isset($stats[$element]) ? $stats[$element] : 0,
                $form->get('bonus')->get(),
                $form->get('weapon')->get(),
                $form->get('count')->get()
            );
            $this->successRollDisplay($success);
            return;
        }

        $this->getHtml('Action/Roll/Success')
            ->set('success_form', $form)
            ->sets($success_data)
            ->set('character', $character)
            ->run();
    }

    private function successRollDisplay($success){
        $this->getHtml('Action/Roll/SuccessDisplay')
            ->set('success', $success)
            ->run();
    }

    public function damageRoll(){
        $user = UserController::checkLogged($this->app);
        $character = CharacterController::getCharacter($user, $this->app);

        $damage_data = [
            'weapons' => array_map(function($weapon){
                return ['id' => $weapon->id, 'value' => $weapon->id, 'text' => ucfirst($weapon->_type->_id->name), 'more' => ''];
            }, WeaponModel::loads(WeaponModel::all(), ['type' => ['id']])) //TODO: list character weapons
        ];

        $form = new Form('damage_form', 'Form/Action/Roll/Damage', null, true, $damage_data);
        if(!empty($_POST) && $form->valid($_POST)){
            $damage = DamageRollModel::makeDamageRoll(
                $user->id, $character->id, $character->place,
                $form->get('description')->get(),
                $form->get('considered')->get() ?: false,
                $form->get('damage')->get(),
                $form->get('fixe')->get(),
                $form->get('weapon')->get(),
                $form->get('count')->get()
            );
            $this->damageRollDisplay($damage);
            return;
        }

        $this->getHtml('Action/Roll/Damage')
            ->set('damage_form', $form)
            ->sets($damage_data)
            ->set('character', $character)
            ->run();
    }

    private function damageRollDisplay($damage){
        $this->getHtml('Action/Roll/DamageDisplay')
            ->set('damage', $damage)
            ->run();
    }

    //TODO: StatModif
}