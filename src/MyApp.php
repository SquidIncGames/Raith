<?php

namespace Raith;

use Krutush\App;
use Krutush\HttpException;
use Krutush\Template\Html;
use Krutush\Path;
use Krutush\Database\Connection;

class MyApp extends App{
    public function __construct(array $data = array()){
        if(!isset($data['path']['root']))
            $data['path']['root'] = dirname(__DIR__);
        if(!isset($data['app']['namespace']))
            $data['app']['namespace'] = __NAMESPACE__.'\\Controller\\';

        parent::__construct($data);

        (new Connection(Path::get('config').'/Databases.php'))->connect();
    }

    public function error(HttpException $e){
        http_response_code($e->getHttpCode());
        (new Html('Error'))
            ->set('router', $this->getRouter())
            ->set('code', $e->getHttpCode())
            ->set('message', $e->getMessage())
            ->run();
		exit;
	}
}