<?php

namespace Raith\Model;

use Krutush\Database\Model;
use Krutush\Database\Connection;

class CharacterModel extends Model{
    protected static $TABLE = 'personnage';
    protected static $FIELDS = [
        'id' => 'idpersonnage',
        'surname' => 'nom',
        'firstname' => 'prenom',
        'race',
        'alignment' => 'alignement',
        'history' => 'histoire',
        'description',
        'owner' => 'proprietaire'
    ];
    protected static $ID = 'id';

    public static function findByOwner(int $id): ?array{
        return static::all([$id], static::getField('owner').' = ?');
    }
}