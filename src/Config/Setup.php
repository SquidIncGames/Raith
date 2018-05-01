<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require __DIR__.'/../../vendor/autoload.php';

function tryRun($request){
    try{
        $request->run();
    }catch(\PDOException $e){
        echo $e->getMessage()."\n";
    }
}

function tryInsert($model){
    try{
        $model->runInsert();
    }catch(\PDOException $e){
        echo $e->getMessage()."\n";
    }
}

new Raith\MyApp();
//Create
tryRun(Raith\Model\UserModel::create());
tryRun(Raith\Model\CharacterModel::create());

//Insert
tryInsert(new Raith\Model\UserModel([
    'name' => 'Admin',
    'password' =>  password_hash('P@ssw0rd', PASSWORD_DEFAULT),
    'mail' => 'root@test.fr',
    'discord'=> '195607134858248192'
]));
tryInsert(new Raith\Model\UserModel([
    'name' => 'User1',
    'password' =>  password_hash('P@ssw0rd1', PASSWORD_DEFAULT),
    'mail' => 'user1@test.fr',
    'discord'=> '172029861370527744'
]));
tryInsert(new Raith\Model\UserModel([
    'name' => 'User2',
    'password' =>  password_hash('P@ssw0rd2', PASSWORD_DEFAULT),
    'mail' => 'user2@test.fr',
    'discord'=> '194393853938106368'
]));
tryInsert(new Raith\Model\CharacterModel([
    'firstname' => 'Pierre',
    'surname' => 'Caillou',
    'race' => 'Rock',
    'alignment' => 'Bien Droit',
    'history' => 'Avant il était. Maintenant il sera.',
    'description' => 'Il est rond et mignon.',
    'owner' => 2
]));
?>