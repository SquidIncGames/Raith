<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class RollDiceModel extends Model{
    public const TABLE = 'roll_dices';
    public const FIELDS = [
        'id' => [
            'column' => 'idroll_dice',
            'type' => 'int',
            'primary' => true
        ],
        'roll' => [
            'type' => 'int',
            'foreign' =>  RollModel::class,
            'not_null' => true
        ],
        'value' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];
}