<?php

namespace Raith\Model\Character;

use Krutush\Database\Model;

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
}