<?php

namespace Raith\Controller;

use Raith\MyController;
use Krutush\Template\Html;
use Krutush\Form\Form;

use Raith\Model\User\UserModel;
use Raith\Model\Character\CharacterModel;
use Raith\Model\Custom\SessionModel;
use Raith\Model\Custom\DiscordModel;

class AdminController extends MyController{
    public static function checkAdmin(): UserModel{
        if(SessionModel::isLogged()){
            $user = UserModel::find(SessionModel::getUserId());
            if($user != null){
                if($user->getRole()->isAdmin)
                    return $user;
            }
        }
        
        http_response_code(401);
        echo "Nop";
        exit;
    }

    public function index(){
        static::checkAdmin();
        (new Html('Admin/Index'))->run();
    }

    public function validateUsers(){
        static::checkAdmin();
        
        $visitors = UserModel::allVisitors();
        $html = (new Html('Admin/Validate/Users'));

        if($visitors != null){
            if(!empty($_POST) && isset($_POST['user_id']) && ctype_digit($_POST['user_id'])){
                foreach ($visitors as $visitor) {
                    if($visitor->id == $_POST['user_id']){
                        $visitor->role = 3; //TODO: Nop
                        $visitor->runUpdate();
                        DiscordModel::historique('<@!'.$visitor->discord.'> ('.$visitor->name.') a été accepté sur le site !');
                        break;
                    }
                }
            }
            $visitors = UserModel::allVisitors();
            if($visitors != null) $html->set('visitors', $visitors);
        }
        
        $html->run();
    }
}