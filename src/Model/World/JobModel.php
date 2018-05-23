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
            'foreign' => [
                'model' => StatModel::class,
                'index' => false //Same as PRIMARY
            ]
        ]
    ];

    public static function insertJob(string $name): self{
        $id = StatModel::insertStat($name)->id;
        $job = new JobModel(['id' => $id]);
        $job->runInsert(false);
        return $job;
    }
}