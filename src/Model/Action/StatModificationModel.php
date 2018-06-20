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
                'index' => false //Same as PRIMARY
            ]
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
        $id = ActionModel::insertAction($user, $character, $place, $date, $valid)->id;
        $modification = new StatModificationModel(['id' => $id, 'description' => $description]);
        $modification->runInsert(false);
        foreach($modifications as $stat => $value){
            (new StatModificationLineModel(['modification' => $modification->id, 'stat' => $stat, 'value' => $value]))->runInsert();
        }
        return $modification;
    }

    public static function getCharacterStats(int $characterId, bool $valid = true): array{
        /*
            SELECT stat_modification_lines.stat, sum(stat_modification_lines.value)
            FROM stat_modifications
            JOIN stat_modification_lines ON idstat_modification = stat_modification_lines.modification
            JOIN actions ON idaction = idstat_modification
            WHERE character = ? AND valid = 1
            GROUP BY stat_modification_lines.stat
        */
    }
}