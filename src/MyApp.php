<?php

namespace Raith;

use Krutush\App;
use Krutush\HttpException;
use Krutush\Template\Html;

class MyApp extends App{
    public function __construct(array $data = array()){
        if(!isset($data['path']['root']))
            $data['path']['root'] = dirname(__DIR__);
        if(!isset($data['app']['namespace']))
            $data['app']['namespace'] = __NAMESPACE__.'\\Controller\\';

        //TODO: Add database

        parent::__construct($data);
    }
}