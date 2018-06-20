<?php

namespace Raith\Model\Character;

use Krutush\Database\Model;
use Raith\Model\User\UserModel;
use Raith\Model\World\PlaceModel;

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
            'foreign' => [
                'model' => CharacterAlignmentModel::class,
                'field' => 'id'
            ] //TODO: ToOne
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
            'default' => 1, //FIXME: get default for config
            'not_null' => true,
            'foreign' => PlaceModel::class
        ],
        'owner' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => [
                'model' => UserModel::class,
                'field' => 'id'
            ] //TODO: ToOne
        ],
        'valid' => [
            'type' => 'bit',
            'default' => '0',
            'not_null' => true
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
}