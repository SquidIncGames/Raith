<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class UtilisateurModel extends Model{
    protected static $TABLE = 'utilisateur';
    protected static $FIELDS = [
        'id' => 'idutilisateur',
        'name' => 'nom',
        'mail',
        'discord'
    ];
    protected static $ID = 'id';

    public static function connect($name, $password): ?int{
        $user = Connection::get(static::getDATABASE())
            ->select(array(static::getID(), 'motdepasse'))
            ->from(static::$TABLE)
            ->where(static::getField('name').' = ?')
            ->run(array($name))
            ->fetch();

        if($user == null)
            return null;

        if(password_verify($password, $user['motdepasse']))
            return $user[static::getID()];

        return null;
    }
}