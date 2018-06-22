<?php

namespace Raith\Model\Action;

class CustomRollModel extends RollModel{
    public const TABLE = 'custom_rolls';
    public const FIELDS = [
        'id' => [
            'column' => 'idcustom_roll',
            'type' => 'int',
            'primary' => true,
            'foreign' => [
                'model' => RollModel::class,
                'index' => false //Same as PRIMARY
            ]
        ],
        'roll' => [
            'type' => 'int',
            'not_null' => true
        ]
    ];

    //MAYBE: Add fixe
    public function discordText(): string{
        return $this->_id->_id->discordText().' fait un jet personalisé ('.count($this->_dices).'d'.$this->roll.")\n".
        implode(' ', $this->getDiceValues())."\n".
        $this->_id->description;
    }

    public static function makeCustomRoll(int $user, int $character, int $place, string $description, int $roll, int $count): self{
        $dices = [];
        for($i = 0; $i < $count; $i++)
            $dices[] = rand(1, $roll);
        return static::insertCustomRoll($user, $character, $place, new \DateTime(), false, $description, $roll, $dices);
    }

    public static function insertCustomRoll(int $user, int $character, int $place, \DateTime $date, bool $valid, string $description, int $roll, array $dices): self{
        $id = static::insertRoll($user, $character, $place, $date, $valid, $description, $dices)->id;
        $damage = new CustomRollModel(compact('id', 'roll'));
        $damage->runInsert(false);
        return $damage;
    }
}