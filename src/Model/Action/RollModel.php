<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class RollModel extends Model{
    public const TABLE = 'rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idroll',
            'type' => 'int',
            'primary' => true,
            'foreign' =>  ActionModel::class
        ],
        'description' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];
    //TODO: get action

    //TODO: get dices
}