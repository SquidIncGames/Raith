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
            'foreign' => [
                'model' => ActionModel::class,
                'index' => false //Same as PRIMARY
            ]
        ],
        'message' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];

    protected $_action;
    public function getAction(bool $update = false): ActionModel{
        if(!isset($_action) || $update)
            $_action = ActionModel::find($this->id);

        return $_action;
    }

    public function validate(){
        $this->getAction(true)->validate();
    }
}