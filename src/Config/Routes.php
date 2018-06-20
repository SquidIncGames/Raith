<?php
$r->add('/', 'Home#index')->name('index');

//World
$r->add('/world', 'World#index')->name('world');
$r->add('/world/list', 'World#list')->name('world list');
$r->add('/world/weather', 'World#weather')->name('world weather');
$r->add('/world/region/:id', 'World#region')->name('world region')
    ->with('id', '\d+');
$r->add('/world/place/:id', 'World#place')->name('world place')
    ->with('id', '\d+');

//TODO: must be logged group

//User
$r->add('/login', 'User#login')->name('login');
$r->add('/logout', 'User#logout')->name('logout');
$r->add('/register', 'User#register')->name('register');
$r->add('/user/:id', 'User#infos')->name('user')
    ->with('id', '\d+');

//Character
$r->add('/characters', 'Character#index')->name('characters');
$r->add('/character/:id', 'Character#infos')->name('character')
    ->with('id', '\d+');
$r->add('/character/create', 'Character#create')->name('character create');

//Admin
$r->add('/admin', 'Admin#index');
$r->add('/admin/validate/users', 'Admin#validateUsers')->name('admin validate users');
$r->add('/admin/validate/characters', 'Admin#validateCharacters')->name('admin validate characters');
$r->add('/admin/validate/actions', 'Admin#validateActions')->name('admin validate actions');

//Random
$r->add('/random', 'Random#index');
$r->add('/random/action', 'Random#action')->name('random action');

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