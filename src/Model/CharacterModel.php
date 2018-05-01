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
            'lenght' => 10
        ],
        'alignment' => [
            'type' => 'varchar', //MAYBE: Alignment Table
            'lenght' => 20
        ],
        'history' => [
            'type' => 'text'
        ],
        'description' => [
            'type' => 'text'
        ],
        'owner' => [
            'type' => 'int', //TODO: OneToOne User
            'not_null' => true
        ]
    ];

    public static function allByOwner(int $userId): array{
        return static::all([$userId], static::getColumn('owner').' = ?');
    }
}