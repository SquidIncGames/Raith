<?php

namespace Raith\Model;

class RandomModel{
    public static function random(int $min, int $max): int{
        return rand($min, $max);
    }

    public static function dice(int $max): int{
        return static::random(1, $max);
    }

    public static function action(int $statistique){
        $value = static::dice(100);
        return [
            'value' => $value,
            'state' => $value  <= 5 ? 'réussite critique !!' : (
                $value  > 95 ? 'échec critique !!' : (
                    $value < $statistique ? 'réussite' : 'echec'
                )
            )
        ];
    }
}