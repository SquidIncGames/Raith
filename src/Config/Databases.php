<?php return array(
    'default' => array(
        'driver' => 'mysql',
        'host' => 'localhost',
    //  'port' => '3306',
        'schema' => 'mydatabase',
        'charset' => 'uft8',
        'username' => 'webuser',
        'password' => 'xxxxxxxxx',
        'options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Exceptions pour les erreurs sql
            PDO::ATTR_PERSISTENT => true, //Connection persistente
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //Pas d'index
        )
    )
);