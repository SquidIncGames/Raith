<?php

namespace Raith\Model\Action;

use Raith\Model\World\ElementModel;
use Raith\Model\World\WeaponModel;

class SuccessRollModel extends RollModel{
    public const TABLE = 'success_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idsuccess_roll',
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
        'elementType' => [
            'column' => 'element_type',
            'type' => 'int',
            'foreign' =>  ElementModel::class,
            'not_null' => true
        ],
        'elementValue' => [
            'column' => 'element_value',
            'type' => 'int',
            'not_null' => true
        ],
        'bonus' => [
            'type' => 'int',
            'not_null' => true
        ],
        'weapon' => [
            'type' => 'int',
            'foreign' => [
                'model' => WeaponModel::class,
                'on_delete' => 'set null'
            ]
        ]
    ];

    public const CRITICAL_SUCCESS = 2;
    public const SUCCESS = 1;
    public const FAILURE = 0;
    public const CRITICAL_FAILURE = -1;

    public static function makeSuccessRoll(int $user, int $character, int $place, string $description, bool $considered, int $elementType, int $elementValue, int $bonus, ?int $weapon, int $count): self{
        $dices = [];
        for($i = 0; $i < $count; $i++)
            $dices[] = rand(1, 100);
        return static::insertSuccessRoll($user, $character, $place, new \DateTime(), false, $description, $considered, $elementType, $elementValue, $bonus, $weapon, $dices);
    }

    public static function insertSuccessRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, bool $considered, int $elementType, int $elementValue, int $bonus, ?int $weapon, array $dices): self{
        $id = static::insertRoll($user, $character, $place, $date, $valid, $description, $dices)->id;
        $success = new SuccessRollModel(compact('id', 'considered', 'elementType', 'elementValue', 'bonus', 'weapon'));
        $success->runInsert(false);
        return $success;
    }

    public function discordText(): string{ //TODO: add weapon
        return $this->_id->_id->discordText().' fait un jet de réussite ['.$this->_elementType->_id->name.'('.$this->elementValue.')+'.$this->bonus.' = '.$this->getSuccessLimit().'] ('.count($this->_dices)."d100)\n".
        implode(', ', $this->getResultsText())."\n".
        $this->_id->description;
    }

    public function getXp(): int{
        if(!$this->considered)
            return 0;

        return array_sum(array_map(function($value){
            return 100-$value;
        }, $this->getDiceValues()))/10;
    }

    public function getSuccessLimit(): int{
        return $this->elementValue + $this->bonus;
    }

    public function getResults(): array{
        return array_map(function($value){
            return static::isSuccess($value, $this->getSuccessLimit());
        }, $this->getDiceValues());
    }

    public static function isSuccess(int $value, int $limit): int{
        return $value  <= 5 ? static::CRITICAL_SUCCESS : (
            $value > 95 ? static::CRITICAL_FAILURE : (
                $value <= $limit ? static::SUCCESS : static::FAILURE
            )
        );
    }

    public function getResultsText(): array{
        return array_map(function($value){
            $action = static::isSuccess($value, $this->getSuccessLimit());
            return static::toText($action).' ('.$value.')';
        }, $this->getDiceValues());
    }

    public static function toText(int $action): ?string{
        switch ($action) {
            case static::CRITICAL_SUCCESS:
                return 'réussite critique !!';
                break;

            case static::SUCCESS:
                return 'réussite';
                break;

            case static::FAILURE:
                return 'échec';
                break;

            case static::CRITICAL_FAILURE:
                return 'échec critique !!';
                break;

            default:
                return null;
                break;
        }
    }
}