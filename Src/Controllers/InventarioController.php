<?php

namespace App\Controllers;

use App\Models\InventarioModel;
use App\Config\ResponseHTTP;

class InventarioController {
    private $method;
    private $route;
    private $params;
    private $data;
    private $headers;

    public function __construct($method,$route,$params,$data,$headers){
        $this->method=$method;
        $this->route=$route;
        $this->params=$params;
        $this->data=$data;
        $this->headers=$headers;
    }

    public function execute($resource){
        switch($this->method){
            case 'post':
                $model = new InventarioModel($this->data);
                echo json_encode($model::asociarProducto());
                break;
            case 'get':
                echo json_encode(InventarioModel::getStock());
                break;
            case 'put':
                $model = new InventarioModel($this->data);
                echo json_encode($model::actualizarStock());
                break;
            case 'delete':
                $model = new InventarioModel($this->data);
                echo json_encode($model::eliminarInventario());
                break;
            default:
                echo json_encode(ResponseHTTP::status404("Método no soportado"));
        }
    }
}
