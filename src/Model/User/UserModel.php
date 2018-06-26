<?php

namespace Raith\Model\User;

use Krutush\Database\Model;
use Raith\Model\Character\CharacterModel;
use Raith\Model\SettingModel;

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
            'foreign' => UserRoleModel::class
        ]
    ];
    public const FOREIGNS = [
        'characters' => [
            'model' => CharacterModel::class,
            'for' => 'id',
            'field' => 'owner',
            'nullable' => true,
            'multiple' => true
        ],
        'rights' => [
            'model' => UserCharacterRightModel::class,
            'for' => 'id',
            'field' => 'user',
            'nullable' => true,
            'multiple' => true
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
        return static::all([SettingModel::value('role_visitor')], static::getColumn('role').' = ?');
    }

    public function getCharacters(string $rightType): array{
        $characters = $this->_characters;
        if($rightType != null){
            foreach(UserCharacterRightModel::load($this->_rights, 'character') as $right){
                if($right->$rightType)
                    $characters[] = $right->_character;
            }
        }
        return $characters;
    }
}