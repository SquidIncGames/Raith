<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\UserModel;
use Raith\Model\CharacterModel;
use Raith\Model\SessionModel;
use Raith\Model\DiscordModel;

class UserController extends MyController{
    public static function checkLogged($app): int{
        if(SessionModel::isLogged())
            return SessionModel::getUserId();
        
        $router = $app->getRouter(); //TODO: redirect link page
        $router->redirect($router->get('login')->getUrl());
    }

    public function login(){
        if(!SessionModel::isLogged()){
            $form = new Form('login_form', 'Form/User/Login');
            if(!empty($_POST) && $form->valid($_POST)){
                $id = UserModel::connect($form->get('mail')->get(), $form->get('password')->get());
                if($id == null){
                    $form->error('Les champs mail et mot de passe ne correspondent pas');
                }else{
                    SessionModel::login($id);
                }
            }
        }
        $router = $this->app->getRouter();
        if(SessionModel::isLogged()){
            $router->redirect($router->get('characters')->getUrl()); //MAYBE: or next_page if 403
        }else{
            (new Html('User/Login'))
                ->set('login_form', $form)
                ->set('register_url', $router->get('register')->getUrl())
                ->run();
        }
    }

    public function logout(){
        (new Html('User/Logout'))
            ->set('logout', SessionModel::logout() ? 'Déconnecté' : 'Pas connecté')
            ->run();
    }

    public function register(){
        $router = $this->app->getRouter();
        $login_url =  $router->get('login')->getUrl();

        $form = new Form('register_form', 'Form/User/Register');
        if(SessionModel::isLogged()){
            $form->error('Vous êtes déjà connecté');
        }else{
            if(!empty($_POST) && $form->valid($_POST)){
                $values = $form->values();
                if($values['password'] != $values['password-bis']){
                    $form->error('Le mot de passe et la validation ne correspondent pas');
                }else{
                    $newUser = new UserModel([
                        'name' => $values['username'],
                        'password' =>  password_hash($values['password'], PASSWORD_DEFAULT),
                        'mail' => $values['mail'],
                        'discord'=> $values['discord']
                    ]);

                    
                    try { //TODO: ugly $newUser->checkUniqueFields()
                        try {
                            $newUser->runInsert();
                            (new Html('User/Registed'))
                                ->set('login_url', $login_url)
                                ->run();
                            DiscordModel::send('<@!'.$newUser->discord.'> s\'est inscrit sur le site !');
                            return;
                        } catch (\PDOException $e){
                            if($e->getCode() != '23000')
                                throw $e;

                            $message = $e->getMessage();
                            if(strpos($message, 'Duplicate entry') === FALSE)
                                throw $e;

                            $field = substr($message, strpos($message, '\'UC_')+strlen('\'UC_'), -1);
                            $form->error('Le '.$field.' est déjà utilisé');
                        }
                    } catch (\Exception $e) {
                        $form->error('Erreur inconnue durant l\'inscription');
                    }
                }
            }
        }
        (new Html('User/Register'))
            ->set('register_form', $form)
            ->set('login_url', $login_url)
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