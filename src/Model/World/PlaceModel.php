<?php

namespace Raith\Model\World;

use Krutush\Database\Model;
use Raith\Model\Character\CharacterModel;

class PlaceModel extends Model{
    public const TABLE = 'places';
    public const FIELDS = [
        'id' => [
            'column' => 'idplace',
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
        'discord' => [
            'type' => 'varchar',
            'lenght' => 20,
            'not_null' => true,
            'unique' => true
        ],
        'region' => [
            'type' => 'int',
            'not_null' => true,
            'foreign' => RegionModel::class
        ]
    ];

    public const FOREIGNS = [
        'characters' => [
            'model' => CharacterModel::class,
            'for' => 'id',
            'field' => 'place',
            'nullable' => true,
            'multiple' => true
        ],
        'roads' => [ //NOTE: Just Many To Many ...
            'model' => RoadModel::class,
            'for' => 'id',
            'field' => 'current',
            'nullable' => true,
            'multiple' => true
        ]
    ];

    public function getFullName(): string{
        return $this->_region->name.' / '.$this->name;
    }
}