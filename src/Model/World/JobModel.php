<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

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
}