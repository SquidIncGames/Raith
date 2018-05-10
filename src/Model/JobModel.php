<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class JobModel extends Model{
    public const TABLE = 'jobs';
    public const FIELDS = [
        'id' => [
            'column' => 'idjob',
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

    public static function find(int $id): ?self{
        return static::first([$id], static::getColumn('id').' = ?');
    }
}