<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class CharacterAlignmentModel extends Model{
    public const TABLE = 'character_alignments';
    public const FIELDS = [
        'id' => [
            'column' => 'idcharacter_alignment',
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
        'description' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];

    public static function find(int $id): ?self{
        return static::first([$id], static::getColumn('id').' = ?');
    }
}