<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\UserModel;
use Raith\Model\CharacterModel;
use Raith\Model\SessionModel;

class UserController extends MyController{
    public static function checkLogged(): int{
        if(SessionModel::isLogged())
            return SessionModel::getUserId();
        
        http_response_code(403);
        echo 'Must be logged'; //TODO: redirect to login ?
        exit();
    }

    public function login(){
        if(!SessionModel::isLogged()){
            $form = new Form('login_form', 'Form/Login');
            if(!empty($_POST) && $form->valid($_POST)){
                $id = UserModel::connect($form->get('username')->get(), $form->get('password')->get());
                if($id == null){
                    $form->error('Les champs nom et mot de passe ne correspondent pas');
                }else{
                    SessionModel::login($id); //TODO: success ?
                }
            }
        }
        if(SessionModel::isLogged()){
            echo 'Allready logged as '.SessionModel::getUserId(); //TODO: redirect to profil ?
        }else{
            (new Html('User/Login'))
                ->set('login_form', $form)
                ->run();
        }
    }

    public function logout(){
        (new Html('User/Logout'))
            ->set('logout', SessionModel::logout() ? 'unlogged' : 'not logged')
            ->run();
    }

    public function characters(){
        $id = UserController::checkLogged();

        $characters = CharacterModel::byOwner($id);
        if($characters == null){
            echo 'Any character';
        }else{
            var_dump($characters);
        }
    }
}