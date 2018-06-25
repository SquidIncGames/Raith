<?php

namespace Raith\Model;

use Krutush\Database\Model;

class SettingModel extends Model{
    public const TABLE = 'settings';
    public const FIELDS = [
        'key' => [
            'column' => 'keysetting',
            'type' => 'varchar',
            'lenght' => 50,
            'primary' => true,
        ],
        'type' => [
            'type' => 'varchar',
            'lenght' => 10,
        ],
        'value' => [
            'type' => 'varchar',
            'lenght' => 200,
            'not_null' => true
        ]
    ];
    public const ID = 'key';

    public static function tryValue(string $key, $default = null){
        $setting = static::find($key);
        if($setting == null)
            return $default;

        return static::convertValue($setting->value, $setting->type);
    }

    public static function value(string $key){
        $setting = static::findOrFail($key);
        return static::convertValue($setting->value, $setting->type);
    }

    public static function values(array $keys): array{
        $values = [];
        foreach(static::findsOrFail($keys) as $setting){
            $values[$setting->key] = static::convertValue($setting->value, $setting->type);
        }
        return $values;
    }

    private static function convertValue(string $value = null, string $type = null){
        switch($type){
            case 'int':
                return intval($value);

            case 'bool':
                return boolval($value);

            default:
                return $value;
        }
    }
}