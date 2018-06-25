<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

use Raith\Model\User\UserModel;
use Raith\Model\Character\CharacterModel;
use Raith\Model\World\PlaceModel;

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
        ],
        'valid' => [
            'type' => 'bit',
            'default' => '0',
            'not_null' => true
        ]
    ];

    public const FOREIGNS = [
        'roll' => [
            'model' => RollModel::class,
            'for' => 'id',
            'nullable' => true
        ],
        'message' => [
            'model' => MessageModel::class,
            'for' => 'id',
            'nullable' => true
        ],
        'stat_modification' => [
            'model' => StatModificationModel::class,
            'for' => 'id',
            'nullable' => true
        ]
    ];

    //TODO: move, ...
    public static function insertAction(int $user, int $character, int $place, \DateTime $date, bool $valid): self{
        $action = new ActionModel(compact('user', 'character', 'place', 'date', 'valid'));
        $action->runInsert();
        return $action;
    }

    public static function allByCharacter(int $characterId, bool $valid = true): array{
        return static::all([$characterId], static::getColumn('character').' = ?'.($valid ? ' AND '.static::getColumn('valid').' = 1' : ''));
    }

    public static function allByValidity(bool $valid): array{
        return static::all([], static::getColumn('valid').' = '.intval($valid));
    }

    public function validate(){
        if(!$this->valid){
            $this->valid = true;
            $this->runUpdate();
        }
    }

    public function discordText(): string{
        return '<#'.$this->_place->discord.'> <@!'.$this->_user->discord.'> ('.$this->_user->name.'): '.$this->_character->getFullName();
    }
}