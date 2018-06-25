<?php

namespace Raith\Model\World;

use Krutush\Database\Model;
use Raith\Model\Custom\DiscordModel;

class RegionModel extends Model{
    public const TABLE = 'regions';
    public const FIELDS = [
        'id' => [
            'column' => 'idregion',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 40,
            'not_null' => true,
            'unique' => true
        ],
        'weather' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => WeatherTypeModel::class
        ],
        'weather_update' => [
            'type' => 'datetime',
            'not_null' => true
        ]
    ];

    public const FOREIGNS = [
        'places' => [
            'model' => PlaceModel::class,
            'for' => 'id',
            'field' => 'region',
            'nullable' => true,
            'multiple' => true
        ]
    ];

    public static function updateWeathers(array $regions){
        $weathers = [];
        foreach(WeatherTypeModel::load(WeatherTypeModel::all(), 'changes') as $weather)
            $weathers[$weather->id] = $weather;

        foreach($regions as $region){
            if((new \DateTime($region->weather_update))->add(new \DateInterval('PT1H')) < new \DateTime()){
                $current = $region->weather;
                $region->weather = $weathers[$region->weather]->getNext();
                $region->set('weather', $weathers[$region->weather]);
                $region->weather_update = new \DateTime();
                $region->runUpdate();
                DiscordModel::meteoraith($region->name.': le temps est '.$region->_weather->name);
            }
        }
        return $regions;
    }
}