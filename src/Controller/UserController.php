<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\User\UserModel;
use Raith\Model\Custom\SessionModel;
use Raith\Model\Custom\DiscordModel;

class UserController extends MyController{
    public static function checkLogged($app): UserModel{
        if(SessionModel::isLogged()){
            $user = UserModel::find(SessionModel::getUserId());
            if($user != null)
                return $user;
        }
        
        $router = $app->getRouter(); //TODO: redirect link page
        $router->redirect($router->get('login')->getUrl());
    }

    public function login(){
        if(!SessionModel::isLogged()){
            $form = new Form('login_form', 'Form/User/Login');
            if(!empty($_POST) && $form->valid($_POST)){
                $user = UserModel::connect($form->get('mail')->get(), $form->get('password')->get());
                if($user == null){
                    $form->error('Les champs mail et mot de passe ne correspondent pas');
                }else{
                    if(!$user->_role->canConnect){
                        $form->error('Votre compte n\'est pas autorisé à se connecter');
                    }else{
                        SessionModel::login($user->id);
                    }
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
                            DiscordModel::inscription('<@!'.$newUser->discord.'> ('.$newUser->name.') s\'est inscrit sur le site !');
                            (new Html('User/Registed'))
                                ->set('login_url', $login_url)
                                ->run();
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

    public function test(){
        DiscordModel::inscription('yolo');
        echo 'End';
    }
}