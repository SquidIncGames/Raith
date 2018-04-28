<?php

namespace Raith\Template;

class Json{
    public static function send($data){
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function run(string $name, $data, int $code = 200){
        $array = [
            $name => $data
        ];
        if($code != 200){
            http_response_code($code);
            $array['success'] = false;
        }else{
            $array['success'] = true;
        }
        static::send($array);
    }

    public static function error(string $error = 'internal error', int $code = 500){
        static::run('error', $error, $code);
    }
}