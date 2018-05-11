<?php

namespace Raith\Model\Custom;

class SessionModel{
    private static $user = 'user_id';
    private static $character = 'character_id';

    public static function isLogged(): bool{
        //FIXME: Add IP Check
        if(session_status() == PHP_SESSION_NONE) session_start();
        return isset($_SESSION[static::$user]) && is_int($_SESSION[static::$user]);
    }

    public static function getUserId(): ?int{
        return static::isLogged() ? $_SESSION[static::$user] : null;
    }

    public static function login(int $user_id): bool{
        if(static::isLogged())
            return false;

        $_SESSION[static::$user] = $user_id;
        return true;
    }

    public static function logout(): bool{
        if(!static::isLogged())
            return false;

        unset($_SESSION[static::$user]);
        return true;
    }

    public static function haveCharacterId(): bool{
        return isset($_SESSION[static::$character]) && is_int($_SESSION[static::$character]);
    }

    public static function getCharacterId(): ?int{
        return static::haveCharacterId() ? $_SESSION[static::$character] : null;
    }

    public static function setCharacterId(int $character_id): bool{
        if(!static::isLogged())
            return false;

        $_SESSION[static::$character] = $character_id;
        return true;
    }
}