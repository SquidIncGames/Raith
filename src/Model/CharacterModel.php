<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class CharacterModel extends Model{
    public const TABLE = 'personnage';
    public const FIELDS = [
        'id' => [
            'column' => 'idpersonnage',
            'type' => 'int',
            'not_null' => true,
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'surname' => [
            'column' => 'nom',
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true
        ],
        'firstname' => [
            'column' => 'prenom',
            'type' => 'varchar',
            'lenght' => 50
        ],
        'race' => [
            'type' => 'varchar', //MAYBE: Race Table
            'lenght' => 10
        ],
        'alignment' => [
            'column' => 'alignement',
            'type' => 'varchar', //MAYBE: Alignment Table
            'lenght' => 20
        ],
        'history' => [
            'column' => 'histoire',
            'type' => 'text'
        ],
        'description' => [
            'type' => 'text'
        ],
        'owner' => [
            'column' => 'proprietaire',
            'type' => 'int', //TODO: OneToOne User
            'not_null' => true
        ]
    ];

    public static function allByOwner(int $userId): array{
        return static::all([$userId], static::getColumn('owner').' = ?');
    }
}