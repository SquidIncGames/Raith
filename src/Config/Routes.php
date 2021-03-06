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

//Action
$r->add('/actions', 'Action#index')->name('actions');
$r->add('/action/message', 'Action#message')->name('action message');
$r->add('/action/roll/custom', 'Action#customRoll')->name('action roll custom');
$r->add('/action/roll/damage', 'Action#damageRoll')->name('action roll damage');
$r->add('/action/roll/success', 'Action#successRoll')->name('action roll success');

//Admin
$r->add('/admin', 'Admin#index');
$r->add('/admin/validate/users', 'Admin#validateUsers')->name('admin validate users');
$r->add('/admin/validate/characters', 'Admin#validateCharacters')->name('admin validate characters');
$r->add('/admin/validate/character/:id', 'Admin#validateCharacter')->name('admin validate character')
    ->with('id', '\d+');
$r->add('/admin/validate/actions', 'Admin#validateActions')->name('admin validate actions');

//API
$r->add('/api/:notfound', 'Home#notfound');