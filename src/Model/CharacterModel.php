<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

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

    protected $alignment;
    public function getAlignment(bool $update = false): ?CharacterAlignmentModel{
        if(!isset($alignment) || $update)
            $alignment = CharacterAlignmentModel::find($this->alignment);

        return $alignment;
    }
}