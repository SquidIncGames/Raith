<?php
$r->add('/', 'Home#index');

//Login
$r->add('/login', 'Home#login');
$r->add('/logout', 'Home#logout');

//Random
$r->add('/random', 'Random#index');
$r->add('/random/action', 'Random#action');


//API
//Random
$r->add('/api/random/:min/:max', 'Random#api_random')
    ->with('min', '\d+')
    ->with('max', '\d+');
$r->add('/api/randoms/:count/:min/:max', 'Random#api_randoms')
    ->with('count', '\d+')
    ->with('min', '\d+')
    ->with('max', '\d+');
$r->add('/api/random/action/:reussite', 'Random#api_action')
    ->with('reussite', '\d+');
$r->add('/api/randoms/:count/action/:reussite', 'Random#api_actions')
    ->with('count', '\d+')
    ->with('reussite', '\d+');
$r->add('/api/random/dice/:max', 'Random#api_dice')
    ->with('max', '\d+');
$r->add('/api/randoms/:count/dice/:max', 'Random#api_dices')
    ->with('count', '\d+')
    ->with('max', '\d+');


$r->add('/api/:notfound', 'Home#notfound');