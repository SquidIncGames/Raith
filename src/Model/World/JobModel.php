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

    protected $_stat;
    public function getStat(bool $update = false): StatModel{
        if(!isset($_stat) || $update)
            $_stat = StatModel::find($this->id);

        return $_stat;
    }

    public static function insertJob(string $name): self{
        $id = StatModel::insertStat($name)->id;
        $job = new JobModel(['id' => $id]);
        $job->runInsert(false);
        return $job;
    }
}