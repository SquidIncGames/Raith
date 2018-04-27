<?php

namespace Raith\Model;

class SessionModel{
    private static $user = 'user_id';

    public static function isLogged(): bool{
        //FIXME: Add IP Check
        session_start();
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
}