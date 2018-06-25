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
        'roads_from' => [
            'model' => RoadModel::class,
            'for' => 'id',
            'field' => 'place_from',
            'nullable' => true,
            'multiple' => true
        ],
        'roads_to' => [
            'model' => RoadModel::class,
            'for' => 'id',
            'field' => 'place_to',
            'nullable' => true,
            'multiple' => true
        ]
    ];

    public function getFullName(): string{
        return $this->_region->name.' / '.$this->name;
    }

    public function getLinkedPlaces(): array{ //NOTE: SQL doesn't like undirected graphs
        return array_map(function($road){ return $road->_place_to; }, RoadModel::load($this->_roads_from, 'place_to'))
             + array_map(function($road){ return $road->_place_from; }, RoadModel::load($this->_roads_to, 'place_from'));
    }
}