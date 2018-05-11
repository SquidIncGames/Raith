<?php

namespace Raith\Model\Action;

//TODO: use Raith\Model\WeaponModel;

class DamageRollModel extends RollModel{
    public const TABLE = 'damage_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'iddamage_roll',
            'type' => 'int',
            'primary' => true,
            'foreign' =>  RollModel::class
        ],
        'considered' => [
            'type' => 'bit',
            'not_null' => true
        ],
        'roll' => [
            'type' => 'int',
            'not_null' => true
        ],
        'weapon' => [
            'type' => 'int',
            'foreign' => WeaponModel::class,
            'not_null' => true
        ]
    ];
    //TODO: get roll
}