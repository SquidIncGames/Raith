<?php

namespace Raith\Model\World;

use Krutush\Database\Model;

class WeatherTypeModel extends Model{
    public const TABLE = 'weather_types';
    public const FIELDS = [
        'id' => [
            'column' => 'idweather_type',
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

    public const FOREIGNS = [
        'changes' => [
            'model' => WeatherChangeModel::class,
            'for' => 'id',
            'field' => 'current',
            'nullable' => true,
            'multiple' => true
        ]
    ];

    public function getNext(): int{
        $changes = $this->_changes;
        if(!isset($changes) || empty($changes))
            return $this->id;

        $max = array_sum(array_map(function ($change){
            return $change->probability;
        }, $changes));
        $value = rand(1, $max);
        foreach($changes as $change){
            $value -= $change->probability;
            if($value <= 0)
                return $change->to;
        }
        return $this->id; //Fallback normaly useless
    }
}