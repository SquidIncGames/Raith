<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
require __DIR__.'/../../vendor/autoload.php';

new Raith\MyApp();

$models = [
    Raith\Model\UserRoleModel::class,
    Raith\Model\UserModel::class,
    Raith\Model\CharacterModel::class
];

$options = getopt("dci", ["drop", "create", "insert"]);

function tryRun($request){
    try{
        return $request->run() ? 'OK' : 'Error';
    }catch(\PDOException $e){
        return $e->getMessage();
    }        
}

function tryInsert($model){
    try{
        $model->runInsert();
    }catch(\PDOException $e){
        echo $e->getMessage();
    }
}

if(isset($options['d']) || isset($options['drop'])){
    echo "drop...".PHP_EOL;
    foreach ($models as $model) {
        echo '  '.$model::TABLE.': '.tryRun($model::drop()).PHP_EOL;
    }
}
if(isset($options['c']) || isset($options['create'])){
    echo "create...".PHP_EOL;
    foreach ($models as $model) {
        echo '  '.$model::TABLE.': '.tryRun($model::create()).PHP_EOL;
    }
}
if(isset($options['i']) || isset($options['insert'])){
    echo "insert...".PHP_EOL;
    $db = Krutush\Database\Connection::get();

    //Roles
    echo 'user roles'.PHP_EOL;
    tryInsert(new Raith\Model\UserRoleModel([
        'name' => 'Administrateur',
        'canConnect' => true,
        'isAdmin' => true
    ]));
    $roles['admin'] = $db->getLastInsertId();
    tryInsert(new Raith\Model\UserRoleModel([
        'name' => 'Nouveau',
        'canConnect' => false,
        'isAdmin' => false
    ]));
    $roles['new'] = $db->getLastInsertId();
    print_r($roles);
    tryInsert(new Raith\Model\UserRoleModel([
        'name' => 'Utilisateur',
        'canConnect' => true,
        'isAdmin' => false
    ]));
    $roles['user'] = $db->getLastInsertId();
    print_r($roles);

    //Users
    echo 'users'.PHP_EOL;
    tryInsert(new Raith\Model\UserModel([
        'name' => 'Admin',
        'password' =>  password_hash('P@ssw0rd', PASSWORD_DEFAULT),
        'mail' => 'root@test.fr',
        'discord'=> '195607134858248192',
        'role' => $roles['admin']
    ]));
    $users['admin'] = $db->getLastInsertId();
    tryInsert(new Raith\Model\UserModel([
        'name' => 'User1',
        'password' =>  password_hash('P@ssw0rd1', PASSWORD_DEFAULT),
        'mail' => 'user1@test.fr',
        'discord'=> '172029861370527744',
        'role' => $roles['user']
    ]));
    $users['user1'] = $db->getLastInsertId();
    tryInsert(new Raith\Model\UserModel([
        'name' => 'User2',
        'password' =>  password_hash('P@ssw0rd2', PASSWORD_DEFAULT),
        'mail' => 'user2@test.fr',
        'discord'=> '194393853938106368',
        'role' => $roles['new']
    ]));
    $users['user2'] = $db->getLastInsertId();
    print_r($users);

    //Characters
    tryInsert(new Raith\Model\CharacterModel([
        'firstname' => 'Pierre',
        'surname' => 'Caillou',
        'race' => 'Rock',
        'alignment' => 'Bien Droit',
        'history' => 'Avant il était. Maintenant il sera.',
        'description' => 'Il est rond et mignon.',
        'owner' => $users['user2']
    ]));
}
echo 'end'.PHP_EOL;
?>