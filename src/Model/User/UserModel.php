<?php

namespace Raith\Model\User;

use Krutush\Database\Model;
use Raith\Model\Character\CharacterModel;

class UserModel extends Model{
    public const TABLE = 'users';
    public const FIELDS = [
        'id' => [
            'column' => 'iduser',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true,
            'unique' => true
        ],
        'mail' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true,
            'unique' => true
        ],
        'discord' => [
            'type' => 'varchar',
            'lenght' => 20,
            'not_null' => true,
            'unique' => true
        ],
        'password' => [
            'type' => 'varchar',
            'lenght' => 60,
            'not_null' => true
        ],
        'role' => [
            'type' => 'int',
            'not_null' => true,
            'default' => 2, //TODO: Nop
            'foreign' => [
                'model' => UserRoleModel::class,
                'field' => 'id'
            ] //TODO: ToOne
        ]
    ];

    public static function connect($mail, $password): ?self{
        $user = static::first([$mail], static::getColumn('mail').' = ?');

        if($user == null)
            return null;

        if(password_verify($password, $user->password))
            return $user;

        return null;
    }

    public static function allVisitors(){
        return static::all([2], static::getColumn('role').' = ?'); //TODO: Nop
    }

    protected $_role; //TODO: include in model
    public function getRole(bool $update = false): ?UserRoleModel{
        if(!isset($_role) || $update)
            $_role = UserRoleModel::find($this->role);

        return $_role;
    }

    protected $_characters;
    public function getCharacters(bool $update = false): ?array{
        if(!isset($_characters) || $update)
            $_characters = CharacterModel::allByOwner($this->id);

        return $_characters;
    }
}