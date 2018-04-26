<?php return array(
    'default' => array(
        'driver' => 'mysql',
        'host' => 'localhost',
    //  'port' => '3306',
        'schema' => 'raith',
        'charset' => 'utf8',
        'username' => 'root',
        'password' => '',
        'options' => array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //Exceptions pour les erreurs sql
            PDO::ATTR_PERSISTENT => true, //Connection persistente
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC //Pas d'index
        )
    )
);