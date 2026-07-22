<?php

namespace App\Controllers;

use App\Models\AlmacenModel;
use App\Config\ResponseHTTP;

class AlmacenController {
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
                $model = new AlmacenModel($this->data);
                echo json_encode($model::registrarAlmacen());
                break;
            case 'get':
                echo json_encode(AlmacenModel::getAll());
                break;
            case 'put':
                $model = new AlmacenModel($this->data);
                echo json_encode($model::actualizarAlmacen($this->params[1] ?? null));
                break;
            case 'delete':
                echo json_encode(AlmacenModel::eliminarAlmacen($this->params[1] ?? null));
                break;
            default:
                echo json_encode(ResponseHTTP::status404("Método no soportado"));
        }
    }
}
