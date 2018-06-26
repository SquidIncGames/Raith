<?php

namespace Raith\Model\User;

use Krutush\Database\Model;
use Raith\Model\Character\CharacterModel;

class UserCharacterRightModel extends Model{
    public const TABLE = 'user_character_rights';
    public const ID = null;
    public const FIELDS = [
        'user' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => UserModel::class
        ],
        'character' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => CharacterModel::class
        ],
        'canPlay' => [
            'column' => 'can_play',
            'type' => 'bit',
            'not_null' => true
        ],
        'canEdit' => [
            'column' => 'can_edit',
            'type' => 'bit',
            'not_null' => true
        ],
        'canManage' => [ //Give rights
            'column' => 'can_manage',
            'type' => 'bit',
            'not_null' => true
        ]
    ];
}