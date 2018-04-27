<?php
$r->add('/', 'Home#index');

//Login
$r->add('/login', 'Home#login');
$r->add('/logout', 'Home#logout');

//Random
$r->add('/random/:min/:max', 'Random#random')
    ->with('min', '\d+')
    ->with('max', '\d+');
$r->add('/random/action/:reussite', 'Random#action')
    ->with('reussite', '\d+');
$r->add('/random/dice/:max', 'Random#dice')
    ->with('max', '\d+');