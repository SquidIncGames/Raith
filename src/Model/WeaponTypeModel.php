<?php

namespace Raith\Model;

use Krutush\Database\Model;

class WeaponTypeModel extends Model{
    public const TABLE = 'weapon_types';
    public const FIELDS = [
        'id' => [
            'column' => 'idweapon_type',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 40,
            'not_null' => true,
            'unique' => true
        ]
    ];
}