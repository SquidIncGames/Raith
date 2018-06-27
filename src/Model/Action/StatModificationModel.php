<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

class StatModificationModel extends Model{
    public const TABLE = 'stat_modifications';
    public const FIELDS = [
        'id' => [
            'column' => 'idstat_modification',
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
        'lines' => [
            'model' => StatModificationLineModel::class,
            'for' => 'id',
            'field' => 'modification',
            'multiple' => true
        ]
    ];

    public function validate(){
        $this->_id->validate();
    }

    public static function insertModification(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, array $modifications): self{
        if(empty($modifications))
            throw new \Exception('Empty StatModification');
        $id = ActionModel::insertAction($user, $character, $place, $date, $valid)->id;
        $modification = new StatModificationModel(['id' => $id, 'description' => $description]);
        $modification->runInsert(false);
        foreach($modifications as $stat => $value){
            (new StatModificationLineModel(['modification' => $modification->id, 'stat' => $stat, 'value' => $value]))->runInsert();
        }
        return $modification;
    }

    public static function getCharacterStats(int $characterId){
        $stats = [];
        foreach(StatModificationModel::select()
            ->fields([
                StatModificationLineModel::getColumn('stat', true),
                'sum('.StatModificationLineModel::getColumn('value', true).') AS value'
            ])
            ->join(StatModificationLineModel::TABLE.' ON '.static::getID(true).' = '.StatModificationLineModel::getColumn('modification', true))
            ->join(ActionModel::TABLE.' ON '.ActionModel::getID(true).' = '.static::getID(true), 'INNER', true)
            ->where(ActionModel::getColumn('character', true).' = ? AND '.ActionModel::getColumn('valid', true).' = 1')
            ->groupby(StatModificationLineModel::getColumn('stat', true))
            ->run([$characterId])
            ->fetchAll() as $values){
            $stats[$values['stat']] = $values['value'];
        }
        return $stats;
    }
}