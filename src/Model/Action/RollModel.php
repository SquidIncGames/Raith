<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class RollModel extends ActionModel{
    public const TABLE = 'rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idroll',
            'type' => 'int',
            'primary' => true,
            'foreign' => [
                'model' => ActionModel::class,
                'index' => false //Same as PRIMARY
            ]
        ],
        'description' => [
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

    protected $_dices;
    public function getDices(bool $update = false): array{
        if(!isset($_dices) || $update)
            $_dices = RollDiceModel::all([$this->id], RollDiceModel::getColumn('roll').' = ?');

        return $_dices;
    }

    public function getDiceValues(): array{
        return array_map(function($dice){
            return $dice->value;
        }, $this->getDices());
    }

    public static function insertRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, array $dices): self{
        $id = static::insertAction($user, $character, $place, $date, $valid)->id;
        $roll = new RollModel(['id' => $id, 'description' => $description]);
        $roll->runInsert(false);
        foreach($dices as $dice){
            (new RollDiceModel(['roll' => $roll->id, 'value' => $dice]))->runInsert();
        }
        return $roll;
    }
}