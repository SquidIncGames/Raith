<?php

namespace Raith\Model\Action;

use Raith\Model\World\StatModel;
use Raith\Model\World\WeaponModel;

class SuccessRollModel extends RollModel{
    public const TABLE = 'success_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idsucess_roll',
            'type' => 'int',
            'primary' => true,
            'foreign' =>  RollModel::class
        ],
        'considered' => [
            'type' => 'bit',
            'not_null' => true
        ],
        'statType' => [
            'column' => 'stat_type',
            'type' => 'int',
            'foreign' =>  StatModel::class,
            'not_null' => true
        ],
        'statValue' => [
            'column' => 'stat_value',
            'type' => 'int',
            'not_null' => true
        ],
        'weapon' => [
            'type' => 'int',
            'foreign' => WeaponModel::class
        ]
    ];
    //TODO: get roll
}