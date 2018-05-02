<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

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

    public static function find(int $id): ?self{
        return static::first([$id], static::getColumn('id').' = ?');
    }

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

    protected $role;
    public function getRole(bool $update = false): ?UserRoleModel{
        if(!isset($role) || $update)
            $role = UserRoleModel::find($this->id);

        return $role;
    }

    protected $characters;
    public function getCharacters(bool $update = false): ?array{
        if(!isset($role) || $update)
            $role = CharacterModel::allByOwner($this->id);

        return $role;
    }
}