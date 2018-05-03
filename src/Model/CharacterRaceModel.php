<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class CharacterRaceModel extends Model{
    public const TABLE = 'character_races';
    public const FIELDS = [
        'id' => [
            'column' => 'idcharacter_race',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 20,
            'not_null' => true,
            'unique' => true
        ],
        'lifetime' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true,
        ],
        'appearance' => [
            'type' => 'text',
            'not_null' => true
        ],
        'character' => [
            'type' => 'text',
            'not_null' => true
        ],
        'socity' => [
            'type' => 'text',
            'not_null' => true
        ],
        'conflicts' => [
            'type' => 'text',
            'not_null' => true
        ],
        'faith' => [
            'type' => 'text',
            'not_null' => true
        ],
        'culture' => [
            'type' => 'text',
            'not_null' => true
        ],
        'magic' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];

    public static function find(int $id): ?self{
        return static::first([$id], static::getColumn('id').' = ?');
    }
}