<?php

namespace Raith\Model\Character;

use Krutush\Database\Model;
use Raith\Model\User\UserModel;
use Raith\Model\User\UserCharacterRightModel;
use Raith\Model\World\PlaceModel;
use Raith\Model\World\StatModel;
use Raith\Model\Action\StatModificationModel;
use Raith\Model\Action\ActionModel;

class CharacterModel extends Model{
    public const TABLE = 'characters';
    public const FIELDS = [ //MAYBE: Unique firstname+surname
        'id' => [
            'column' => 'idcharacter',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'surname' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true
        ],
        'firstname' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true
        ],
        'nickname' => [
            'type' => 'varchar',
            'lenght' => 50
        ],
        'race' => [
            'type' => 'varchar',
            'lenght' => 10, //NOTE: Defaults in CharacterRaceModel::class
            'not_null' => true
        ],
        'birthday' => [
            'type' => 'date',
            'not_null' => true
        ],
        'size' => [
            'type' => 'int',
            'not_null' => true
        ],
        'weight' => [
            'type' => 'int',
            'not_null' => true
        ],
        'alignment' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => CharacterAlignmentModel::class,
        ],
        'personality' => [
            'type' => 'text',
            'not_null' => true
        ],
        'description' => [
            'type' => 'text',
            'not_null' => true
        ],
        'history' => [
            'type' => 'text',
            'not_null' => true
        ],
        'place' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => PlaceModel::class
        ],
        'owner' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => UserModel::class,
        ],
        'valid' => [
            'type' => 'bit',
            'default' => '0',
            'not_null' => true
        ]
    ];
    public const FOREIGNS = [
        'rights' => [
            'model' => UserCharacterRightModel::class,
            'for' => 'id',
            'field' => 'character',
            'nullable' => true,
            'multiple' => true
        ]
    ];

    public function getFullName(): string{
        return "$this->surname $this->firstname".(!empty($this->nickname) ? " ($this->nickname)" : '');
    }

    public static function allByOwner(int $userId): array{
        return static::all([$userId], static::getColumn('owner').' = ?');
    }

    public static function allByValidity(bool $valid): array{
        return static::all([], static::getColumn('valid').' = '.intval($valid));
    }

    public function getTotalXp(): int{
        $actions = ActionModel::loads(ActionModel::allByCharacter($this->id), ['roll' => ['success', 'damage']]);
        $xp = 0;
        foreach($actions as $action){
            if($action->_roll != null){
                if($action->_roll->_success != null)
                    $xp += $action->_roll->_success->getXp();

                if($action->_roll->_damage != null)
                    $xp += $action->_roll->_damage->getXp();
            }
        }
        return 42;
    }
}