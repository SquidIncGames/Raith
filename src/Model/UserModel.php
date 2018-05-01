<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class UserModel extends Model{
    public const TABLE = 'utilisateur';
    public const FIELDS = [
        'id' => [
            'column' => 'idutilisateur',
            'type' => 'int',
            'not_null' => true,
            'primary' => true,
            'custom' => 'AUTO_INCREMENT'
        ],
        'name' => [
            'column' => 'nom',
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true
        ],
        'mail' => [
            'type' => 'varchar',
            'lenght' => 50,
            'not_null' => true
        ],
        'discord' => [
            'type' => 'varchar', //MAYBE: int
            'lenght' => 50,
            'not_null' => true
        ],
        'password' => [
            'column' => 'motdepasse',
            'type' => 'varchar',
            'lenght' => 60,
            'not_null' => true
        ]
    ];

    public static function connect($name, $password): ?int{
        $user = static::first([$name], static::getColumn('name').' = ?');

        if($user == null)
            return null;

        if(password_verify($password, $user->password))
            return $user->id;

        return null;
    }
}