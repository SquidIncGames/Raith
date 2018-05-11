<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

use Raith\Model\User\UserModel;
use Raith\Model\Character\CharacterModel;
//TODO: use Raith\Model\PlaceModel;

class ActionModel extends Model{
    public const TABLE = 'actions';
    public const FIELDS = [
        'id' => [
            'column' => 'idaction',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'user' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' =>  UserModel::class
        ],
        'character' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' =>  CharacterModel::class
        ],
        'place' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => PlaceModel::class
        ],
        'date' => [
            'type' => 'datetime',
            'not_null' => true
        ]
    ];
    //TODO: get message, roll, move, ...
}