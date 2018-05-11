<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class MessageModel extends Model{
    public const TABLE = 'messages';
    public const FIELDS = [
        'id' => [
            'column' => 'idmessage',
            'type' => 'int',
            'primary' => true,
            'foreign' =>  ActionModel::class
        ],
        'message' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];
}