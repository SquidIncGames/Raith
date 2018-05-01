<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class UserModel extends Model{
    public const TABLE = 'users';
    public const FIELDS = [
        'id' => [
            'column' => 'iduser',
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
        'mail' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true,
            'unique' => true
        ],
        'discord' => [
            'type' => 'varchar', //MAYBE: bigint
            'lenght' => 20,
            'not_null' => true,
            'unique' => true
        ],
        'password' => [
            'type' => 'varchar',
            'lenght' => 60,
            'not_null' => true
        ]
    ];

    public static function connect($mail, $password): ?int{
        $user = static::first([$mail], static::getColumn('mail').' = ?');

        if($user == null)
            return null;

        if(password_verify($password, $user->password))
            return $user->id;

        return null;
    }
}