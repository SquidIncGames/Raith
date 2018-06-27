<?php

namespace Raith\Model\Action;

use Krutush\Database\Model;

use Raith\Model\World\StatModel;

class StatModificationLineModel extends Model{
    public const TABLE = 'stat_modification_lines';
    public const FIELDS = [
        'id' => [
            'column' => 'idstat_modification_line',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'modification' => [
            'type' => 'int',
            'foreign' => [
                'model' => StatModificationModel::class,
                'on_delete' => 'cascade'
            ],
            'not_null' => true
        ],
        'stat' => [
            'type' => 'int',
            'foreign' => StatModel::class,
            'not_null' => true
        ],
        'value' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];
}