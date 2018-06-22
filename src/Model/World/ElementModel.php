<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class ElementModel extends Model{
    public const TABLE = 'elements';
    public const FIELDS = [
        'id' => [
            'column' => 'idelement',
            'type' => 'int',
            'primary' => true,
            'foreign' => [
                'model' => StatModel::class,
                'index' => false //Same as PRIMARY
            ]
        ]
    ];

    public static function insertElement(string $name): self{
        $id = StatModel::insertStat($name)->id;
        $element = new ElementModel(['id' => $id]);
        $element->runInsert(false);
        return $element;
    }
}