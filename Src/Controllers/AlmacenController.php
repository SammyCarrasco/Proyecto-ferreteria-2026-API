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

    public function __construct($method, $route, $params, $data, $headers){
        $this->method = strtolower($method);
        $this->route = $route;
        $this->params = $params;
        $this->data = is_array($data) ? $data : (array)$data;
        $this->headers = $headers;
    }

    /**
     * Valida que los campos obligatorios vengan en el JSON y no estén vacíos.
     */
    private function validarDatos() {
        // Verificamos que $this->data contenga los campos requeridos
        if (empty($this->data)) {
            return [
                "status" => false,
                "message" => "No se enviaron datos para procesar."
            ];
        }

        $camposRequeridos = ['nombre', 'ubicacion'];
        foreach ($camposRequeridos as $campo) {
            if (!isset($this->data[$campo]) || trim($this->data[$campo]) === '') {
                return [
                    "status" => false,
                    "message" => "El campo '{$campo}' es obligatorio y no puede estar vacío."
                ];
            }
        }

        return ["status" => true];
    }

    public function execute($resource){
        switch($this->method){
            case 'post':
                // Validar antes de registrar
                $validacion = $this->validarDatos();
                if (!$validacion['status']) {
                    echo json_encode(ResponseHTTP::status400($validacion['message']));
                    break;
                }

                $model = new AlmacenModel($this->data);
                echo json_encode($model::registrarAlmacen());
                break;

            case 'get':
                echo json_encode(AlmacenModel::getAll());
                break;

            case 'put':
                // Validar ID presente en la ruta
                $id = $this->params[1] ?? null;
                if (!$id || !is_numeric($id)) {
                    echo json_encode(ResponseHTTP::status400("El ID del almacén es obligatorio y debe ser numérico."));
                    break;
                }

                // Validar antes de actualizar
                $validacion = $this->validarDatos();
                if (!$validacion['status']) {
                    echo json_encode(ResponseHTTP::status400($validacion['message']));
                    break;
                }

                $model = new AlmacenModel($this->data);
                echo json_encode($model::actualizarAlmacen($id));
                break;

            case 'delete':
                $id = $this->params[1] ?? null;
                if (!$id || !is_numeric($id)) {
                    echo json_encode(ResponseHTTP::status400("El ID del almacén es obligatorio y debe ser numérico."));
                    break;
                }

                echo json_encode(AlmacenModel::eliminarAlmacen($id));
                break;

            default:
                echo json_encode(ResponseHTTP::status404("Método no soportado"));
        }
    }
}