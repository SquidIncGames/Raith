<?php

namespace Raith\Model\Character;

use Krutush\Database\Model;
use Raith\Model\User\UserModel;
use Raith\Model\World\PlaceModel;

class CharacterModel extends Model{
    public const TABLE = 'characters';
    public const FIELDS = [
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
            'lenght' => 50
        ],
        'race' => [
            'type' => 'varchar', //MAYBE: Race Table
            'lenght' => 10 //NOTE: Defaults in CharacterRaceModel::class
        ],
        'alignment' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => [
                'model' => CharacterAlignmentModel::class,
                'field' => 'id'
            ] //TODO: ToOne
        ],
        'history' => [
            'type' => 'text'
        ],
        'description' => [
            'type' => 'text'
        ],
        'place' => [
            'type' => 'int',
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
        ]
    ];

    public static function allByOwner(int $userId): array{
        return static::all([$userId], static::getColumn('owner').' = ?');
    }

    protected $_alignment;
    public function getAlignment(bool $update = false): ?CharacterAlignmentModel{
        if(!isset($_alignment) || $update)
            $_alignment = CharacterAlignmentModel::find($this->alignment);

        return $_alignment;
    }
}