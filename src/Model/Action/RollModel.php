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
            'foreign' => [
                'model' => ActionModel::class,
                'on_delete' => 'cascade'
            ],
            'index' => false //Same as PRIMARY
        ],
        'description' => [
            'type' => 'text',
            'not_null' => true
        ]
    ];
    public const FOREIGNS = [
        'dices' => [
            'model' => RollDiceModel::class,
            'for' => 'id',
            'field' => 'roll',
            'multiple' => true
        ],
        'custom' => [
            'model' => CustomRollModel::class,
            'for' => 'id',
            'nullable' => true
        ],
        'damage' => [
            'model' => DamageRollModel::class,
            'for' => 'id',
            'nullable' => true
        ],
        'success' => [
            'model' => SuccessRollModel::class,
            'for' => 'id',
            'nullable' => true
        ],
    ];

    public function validate(){
        $this->_id->validate();
    }

    public function getDiceValues(): array{
        return array_map(function($dice){
            return $dice->value;
        }, $this->_dices);
    }

    public static function insertRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, array $dices): self{
        $id = ActionModel::insertAction($user, $character, $place, $date, $valid)->id;
        $roll = new RollModel(['id' => $id, 'description' => $description]);
        $roll->runInsert(false);
        foreach($dices as $dice){
            (new RollDiceModel(['roll' => $roll->id, 'value' => $dice]))->runInsert();
        }
        return $roll;
    }
}