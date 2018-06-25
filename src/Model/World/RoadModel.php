<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

//TODO: check constraint that current < to
class RoadModel extends Model{
    public const TABLE = 'roads';
    public const ID = null;
    public const FIELDS = [
        'place_from' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => PlaceModel::class
        ],
        'place_to' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => PlaceModel::class
        ]
    ];
}