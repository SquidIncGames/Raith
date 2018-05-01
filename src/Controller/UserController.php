<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\UserModel;
use Raith\Model\CharacterModel;
use Raith\Model\SessionModel;

class UserController extends MyController{
    public static function checkLogged($app): int{
        if(SessionModel::isLogged())
            return SessionModel::getUserId();
        
        $router = $app->getRouter(); //TODO: redirect link page
        $router->redirect($router->get('login')->getUrl());
    }

    public function login(){
        if(!SessionModel::isLogged()){
            $form = new Form('login_form', 'Form/Login');
            if(!empty($_POST) && $form->valid($_POST)){
                $id = UserModel::connect($form->get('username')->get(), $form->get('password')->get());
                if($id == null){
                    $form->error('Les champs nom et mot de passe ne correspondent pas');
                }else{
                    SessionModel::login($id);
                }
            }
        }
        if(SessionModel::isLogged()){
            $router = $this->app->getRouter();
            $router->redirect($router->get('characters')->getUrl()); //MAYBE: or next_page if 403
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
        $id = UserController::checkLogged($this->app);

        $characters = CharacterModel::allByOwner($id);
        if($characters == null){
            echo 'Any character';
        }else{
            if(!empty($_POST) && isset($_POST['character_id']) && ctype_digit($_POST['character_id'])){
                foreach ($characters as $character) {
                    if($character->id == $_POST['character_id']){
                        SessionModel::setCharacterId($character->id);
                        break;
                    }
                }
            }

            (new Html('User/Characters'))
                ->set('current_character', SessionModel::getCharacterId())
                ->set('characters', $characters)
                ->run();
        }
    }
}