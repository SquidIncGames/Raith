<?php

namespace Raith\Model\Action;

class CustomRollModel extends RollModel{
    public const TABLE = 'custom_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idcustom_roll',
            'type' => 'int',
            'primary' => true,
            'foreign' =>  RollModel::class
        ],
        'roll' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];
    //TODO: get roll
}