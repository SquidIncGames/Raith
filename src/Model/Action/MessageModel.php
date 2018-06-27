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
                'on_delete' => 'cascade'
            ],
            'index' => false //Same as PRIMARY
        ],
        'message' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];

    public function validate(){
        $this->_id->validate();
    }

    public function discordText(): string{
        return $this->_id->discordText()." dit \n".$this->message;
    }
}