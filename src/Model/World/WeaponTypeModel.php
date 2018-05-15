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
            'foreign' => [
                'model' => StatModel::class,
                'index' => false //Same as PRIMARY
            ]
        ]
    ];

    protected $_stat;
    public function getStat(bool $update = false): StatModel{
        if(!isset($_stat) || $update)
            $_stat = StatModel::find($this->id);

        return $_stat;
    }

    public static function insertWeaponType(string $name): self{
        $id = StatModel::insertStat($name)->id;
        $weaponType = new WeaponTypeModel(['id' => $id]);
        $weaponType->runInsert(false);
        return $weaponType;
    }
}