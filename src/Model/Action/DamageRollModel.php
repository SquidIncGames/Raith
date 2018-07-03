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
                'on_delete' => 'cascade'
            ],
            'index' => false //Same as PRIMARY
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
            $dices[] = rand(1, $roll);
        return static::insertDamageRoll($user, $character, $place, new \DateTime(), false, $description, $considered, $roll, $fixe, $weapon, $dices);
    }

    public static function insertDamageRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, bool $considered, int $roll, int $fixe, int $weapon, array $dices): self{
        $id = static::insertRoll($user, $character, $place, $date, $valid, $description, $dices)->id;
        $damage = new DamageRollModel(compact('id', 'considered', 'roll', 'fixe', 'weapon'));
        $damage->runInsert(false);
        return $damage;
    }

    public function discordText(): string{ //TODO: add weapon
        return $this->_id->_id->discordText().' fait un jet de dégat ('.count($this->_dices).'d'.$this->roll.')+'.$this->fixe."\n".
        implode(' ', $this->getDiceValues())."\n".
        'Total: '.$this->getDamages()."\n".
        $this->_id->description;
    }

    public function getDamages(): int{
        return array_sum(array_map(function($value){
            return $value;
        }, $this->getDiceValues()))+$this->fixe;
    }

    public function getXp(): int{
        if(!$this->considered)
            return 0;

        return $this->getDamages();
    }
}