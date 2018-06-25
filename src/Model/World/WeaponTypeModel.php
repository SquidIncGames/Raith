<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class WeaponTypeModel extends Model{
    public const TABLE = 'weapon_types';
    public const FIELDS = [
        'id' => [
            'column' => 'idweapon_type',
            'type' => 'int',
            'primary' => true,
            'foreign' => StatModel::class,
            'index' => false //Same as PRIMARY
        ]
    ];

    public static function insertWeaponType(string $name): self{
        $id = StatModel::insertStat($name)->id;
        $weaponType = new WeaponTypeModel(['id' => $id]);
        $weaponType->runInsert(false);
        return $weaponType;
    }
}