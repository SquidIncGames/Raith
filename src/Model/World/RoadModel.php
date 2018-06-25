<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class RoadModel extends Model{
    public const TABLE = 'roads';
    public const ID = null;
    public const FIELDS = [
        'current' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => PlaceModel::class
        ],
        'to' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => PlaceModel::class
        ]
    ];
}