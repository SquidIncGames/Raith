<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class WeatherChangeModel extends Model{
    public const TABLE = 'weather_changes';
    public const ID = null;
    public const FIELDS = [
        'current' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => WeatherTypeModel::class
        ],
        'to' => [
            'type' => 'int',
            'primary' => true,
            'foreign' => WeatherTypeModel::class
        ],
        'probability' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];
}