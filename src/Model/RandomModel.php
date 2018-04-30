<?php

namespace Raith\Model;

class RandomModel{
    public const CRITICAL_SUCCESS = 2;
    public const SUCCESS = 1;
    public const FAILURE = 0;
    public const CRITICAL_FAILURE = -1;

    public static function random(int $min, int $max): int{
        return rand($min, $max);
    }

    public static function dice(int $max): int{
        return static::random(1, $max);
    }

    public static function isSuccess(int $action): bool{
        return $action == self::SUCCESS || $action == self::CRITICAL_SUCCESS;
    }

    public static function actionToText(int $action): ?string{
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

    public static function action(int $statistique){
        $value = static::dice(100);
        $result = $value  <= 5 ? static::CRITICAL_SUCCESS : (
            $value > 95 ? static::CRITICAL_FAILURE : (
                $value <= $statistique ? static::SUCCESS : static::FAILURE
            )
        );
        return [
            'value' => $value,
            'result' => $result,
            'text' => static::actionToText($result)
        ];
    }
}