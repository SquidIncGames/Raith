<?php
$r->add('/', 'Home#index');

//TODO: must be logged group

//User
$r->add('/login', 'User#login')->name('login');
$r->add('/logout', 'User#logout')->name('logout');
$r->add('/register', 'User#register')->name('register');

//Character
$r->add('/characters', 'Character#index')->name('characters');
$r->add('/character/create', 'Character#create');

//Admin
$r->add('/admin', 'Admin#index');
$r->add('/admin/validate/users', 'Admin#validateUsers');

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