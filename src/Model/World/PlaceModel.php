<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class PlaceModel extends Model{
    public const TABLE = 'places';
    public const FIELDS = [
        'id' => [
            'column' => 'idplace',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 40,
            'not_null' => true,
            'unique' => true
        ],
        'discord' => [
            'type' => 'varchar',
            'lenght' => 20,
            'not_null' => true,
            'unique' => true
        ]
    ];
}