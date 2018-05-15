<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class StatModel extends Model{
    public const TABLE = 'stats';
    public const FIELDS = [
        'id' => [
            'column' => 'idstat',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 40,
            'not_null' => true,
            'unique' => true
        ]
    ];

    public static function insertStat(string $name): self{
        $stat = new StatModel(['name' => $name]);
        $stat->runInsert();
        return $stat;
    }
}