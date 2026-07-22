<?php

namespace App\Controllers;

use App\Models\productoModel;
use App\Config\responseHTTP;

class productoController {
    private static $method; 
    private static $route;
    private static $params;
    private static $data;
    private static $headers; 

    public function __construct($method, $route, $params, $data, $headers) {
        self::$method = $method;      
        self::$route = $route;
        self::$params = $params;
        self::$data = $data;
        self::$headers = $headers;            
    }

    /**
     * Método para listar el catálogo de productos
     */
    final public static function getCatalogo($endpoint){
        // Validamos que el método sea GET y que la ruta coincida
        if(self::$method == 'get' && $endpoint == self::$route){ 
            
            $data = productoModel::getCatalogo();
            
            if(!empty($data)){
                echo json_encode([
                    'status' => 200,
                    'message' => 'Catálogo de productos obtenido exitosamente',
                    'data' => $data
                ]);
            } else {
                echo json_encode([
                    'status' => 200,
                    'message' => 'No hay productos registrados en el catálogo',
                    'data' => []
                ]);
            }
            exit;
        }
    }
}
