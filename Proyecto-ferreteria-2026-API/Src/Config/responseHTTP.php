<?php
namespace App\Config; 

class ResponseHTTP{

    public static $mensaje = array(
        'status' => '',
        'message' => '',
        'data' => '', 
        'date' => ''
    );

    final public static function status200($res){
        http_response_code(200); 
        self::$mensaje['status'] = 'OK';
        self::$mensaje['message'] = $res; 
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }

    final public static function status201(){
        $res = 'Recurso creado exitosamente!';
        http_response_code(201);
        self::$mensaje['status'] = 'OK';
        self::$mensaje['message'] = $res;
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }

    final public static function status400($res){
        http_response_code(400);
        self::$mensaje['status'] = 'ERROR';
        self::$mensaje['message'] = $res; 
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }

    final public static function status401($str){
        $res = 'No tiene privilegios para acceder al recurso! '.$str;
        http_response_code(401);
        self::$mensaje['status'] = 'ERROR';
        self::$mensaje['message'] = $res; 
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }

    final public static function status404($res){
        //$res = 'No existe  el recurso solicitado!';
        http_response_code(404);
        self::$mensaje['status'] = 'ERROR';
        self::$mensaje['message'] = $res; 
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }

    final public static function status500(){
        $res = 'Se ha producido un error en el servidor!';
        http_response_code(500);
        self::$mensaje['status'] = 'ERROR';
        self::$mensaje['message'] = $res; 
        self::$mensaje['date'] = date('Y-m-d H:i:s'); 
        return self::$mensaje;
    }
}