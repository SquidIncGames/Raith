<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Raith\Template\Json;
use Krutush\Form\Form;
use Krutush\Database\Connection;

use Raith\Model\UtilisateurModel;
use Raith\Model\SessionModel;

class HomeController extends MyController{
    public function index(){
        (new Html('Index'))->run();
    }

    public function login(){
        if(!SessionModel::isLogged()){
            $form = new Form('login_form', 'Form/Login');
            if(!empty($_POST) && $form->valid($_POST)){
                $id = UtilisateurModel::connect($form->get('username')->get(), $form->get('password')->get());
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
            (new Html('Login'))
                ->set('login_form', $form)
                ->run();
        }
    }

    public function logout(){
        (new Html('Logout'))
            ->set('logout', SessionModel::logout() ? 'unlogged' : 'not logged')
            ->run();
    }

    public function notfound($url){
        Json::error('not_found', 404);
    }
}