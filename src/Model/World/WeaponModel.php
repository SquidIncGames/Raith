<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class WeaponModel extends Model{ //FIXME: Temporary model
    public const TABLE = 'weapons';
    public const FIELDS = [
        'id' => [
            'column' => 'idweapon',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'type' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => WeaponTypeModel::class
        ]
    ];
}