<?php

namespace Raith\Model\Action;

use Raith\Model\World\WeaponModel;

class DamageRollModel extends RollModel{
    public const TABLE = 'damage_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'iddamage_roll',
            'type' => 'int',
            'primary' => true,
            'foreign' => [
                'model' => RollModel::class,
                'index' => false //Same as PRIMARY
            ]
        ],
        'considered' => [
            'type' => 'bit',
            'not_null' => true
        ],
        'roll' => [
            'type' => 'int',
            'not_null' => true
        ],
        'fixe' => [
            'type' => 'int',
            'not_null' => true
        ],
        'weapon' => [
            'type' => 'int',
            'foreign' => WeaponModel::class,
            'not_null' => true
        ]
    ];

    public static function makeDamageRoll(int $user, int $character, int $place, string $description, bool $considered, int $roll, int $fixe, int $weapon, int $count): self{
        $dices = [];
        for($i = 0; $i < $count; $i++)
            $dices[] = rand(1, $roll); //MAYBE: min 1 ?
        return static::insertDamageRoll($user, $character, $place, new \DateTime(), false, $description, $considered, $roll, $fixe, $weapon, $dices);
    }

    public static function insertDamageRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, bool $considered, int $roll, int $fixe, int $weapon, array $dices): self{
        $id = static::insertRoll($user, $character, $place, $date, $valid, $description, $dices)->id;
        $damage = new DamageRollModel(compact('id', 'considered', 'roll', 'fixe', 'weapon'));
        $damage->runInsert(false);
        return $damage;
    }

    public function getDamages(): int{
        throw new \Exception('WIP');
        //FIXME: Fixe * count ???
        return array_sum(array_map(function($value){
            return $value;
        }, $this->getDiceValues()));
    }
    
    public function getXp(): int{
        return $this->getDamages();
    }
}