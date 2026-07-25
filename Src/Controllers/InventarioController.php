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

    public function __construct($method, $route, $params, $data, $headers){
        $this->method = strtolower($method);
        $this->route = $route;
        $this->params = $params;
        $this->data = is_array($data) ? $data : (array)$data;
        $this->headers = $headers;
    }

    /**
     * Valida los campos exactos de la tabla inventario.
     */
    private function validarInventario() {
        if (empty($this->data)) {
            return [
                "status" => false,
                "message" => "No se enviaron datos para procesar."
            ];
        }

        // Campos exactos según tu tabla en la base de datos
        $camposRequeridos = ['id_producto', 'id_almacen', 'stock_disponible', 'stock_reservado'];

        foreach ($camposRequeridos as $campo) {
            // Usamos isset() porque el valor '0' es completamente válido para el stock
            if (!isset($this->data[$campo]) || $this->data[$campo] === '') {
                return [
                    "status" => false,
                    "message" => "El campo '{$campo}' es obligatorio."
                ];
            }

            // Validar que todos sean números enteros y no sean negativos
            if (!is_numeric($this->data[$campo]) || $this->data[$campo] < 0) {
                return [
                    "status" => false,
                    "message" => "El campo '{$campo}' debe ser un valor numérico mayor o igual a cero."
                ];
            }
        }

        return ["status" => true];
    }

    /**
     * Valida que al menos vengan las claves primarias (útil para DELETE)
     */
    private function validarClavesPrimarias() {
        if (empty($this->data['id_producto']) || empty($this->data['id_almacen'])) {
            return [
                "status" => false,
                "message" => "Se requieren 'id_producto' e 'id_almacen' para identificar el registro."
            ];
        }
        
        if (!is_numeric($this->data['id_producto']) || !is_numeric($this->data['id_almacen'])) {
            return [
                "status" => false,
                "message" => "Los identificadores deben ser numéricos."
            ];
        }

        return ["status" => true];
    }

    public function execute($resource){
        switch($this->method){
            case 'post':
                $validacion = $this->validarInventario();
                if (!$validacion['status']) {
                    echo json_encode(ResponseHTTP::status400($validacion['message']));
                    break;
                }

                $model = new InventarioModel($this->data);
                echo json_encode($model::asociarProducto());
                break;

            case 'get':
                echo json_encode(InventarioModel::getStock());
                break;

            case 'put':
                $validacion = $this->validarInventario();
                if (!$validacion['status']) {
                    echo json_encode(ResponseHTTP::status400($validacion['message']));
                    break;
                }

                $model = new InventarioModel($this->data);
                echo json_encode($model::actualizarStock());
                break;

            case 'delete':
                // Al ser clave primaria compuesta, lo ideal es enviar los IDs en el cuerpo (JSON)
                // en lugar de depender de un solo parámetro en la URL.
                $validacion = $this->validarClavesPrimarias();
                if (!$validacion['status']) {
                    echo json_encode(ResponseHTTP::status400($validacion['message']));
                    break;
                }

                $model = new InventarioModel($this->data);
                echo json_encode($model::eliminarInventario());
                break;

            default:
                echo json_encode(ResponseHTTP::status404("Método no soportado"));
        }
    }
}