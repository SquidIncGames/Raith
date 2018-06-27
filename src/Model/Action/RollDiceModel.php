<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class RollDiceModel extends Model{
    public const TABLE = 'roll_dices';
    public const FIELDS = [
        'id' => [
            'column' => 'idroll_dice',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'roll' => [
            'type' => 'int',
            'foreign' => [
                'model' => RollModel::class,
                'on_delete' => 'cascade'
            ],
            'not_null' => true
        ],
        'value' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];
}