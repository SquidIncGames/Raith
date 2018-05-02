<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class UserRoleModel extends Model{
    public const TABLE = 'user_roles';
    public const FIELDS = [
        'id' => [
            'column' => 'iduser_role',
            'type' => 'int',
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true,
            'unique' => true
        ],
        'canConnect' => [
            'column' => 'can_connect',
            'type' => 'bit',
            'not_null' => true
        ],
        'isAdmin' => [
            'column' => 'is_admin',
            'type' => 'bit',
            'not_null' => true
        ]
    ];

    public static function find(int $id): ?self{
        return static::first([$id], static::getColumn('id').' = ?');
    }
}