<?php

namespace App\Controllers;

use App\Models\CotizacionModel;
use App\Config\ResponseHTTP;

class CotizacionController {
    private $method;
    private $route;
    private $params;
    private $data;
    private $headers;

    public function __construct($method, $route, $params, $data, $headers) {
        $this->method  = strtolower($method);
        $this->route   = $route;
        $this->params  = $params;
        $this->data    = is_array($data) ? $data : (array)$data;
        $this->headers = $headers;
    }

    /**
     * Valida el campo id_cliente para la selección.
     */
    private function validarCliente() {
        if (empty($this->data['id_cliente']) || !is_numeric($this->data['id_cliente'])) {
            return [
                "status"  => false,
                "message" => "El campo 'id_cliente' es obligatorio y debe ser un entero válido."
            ];
        }
        return ["status" => true];
    }

    /**
     * Valida el criterio para la búsqueda de productos.
     */
    private function validarBusqueda() {
        $criterio = $this->data['criterio'] ?? $this->data['criterio_producto'] ?? $this->data['busqueda'] ?? null;
        if (empty($criterio)) {
            return [
                "status"  => false,
                "message" => "Se requiere 'criterio_producto' o 'busqueda'."
            ];
        }
        return ["status" => true];
    }

    /**
     * Valida la lista de productos para el cálculo/reserva.
     */
    private function validarProductos() {
        if (empty($this->data['productos']) || !is_array($this->data['productos'])) {
            return [
                "status"  => false,
                "message" => "Se requiere una lista válida en el arreglo 'productos'."
            ];
        }
        return ["status" => true];
    }

    /**
     * Controlador frontal basado en acciones o métodos HTTP.
     */
    public function execute($resource = '') {
        if ($this->method !== 'post') {
            echo json_encode(ResponseHTTP::status405("Método no permitido. Utiliza el método POST."));
            return;
        }

        $accionRaw = $this->data['accion'] ?? $_GET['accion'] ?? '';
        $accion    = strtolower($accionRaw);

        switch ($accion) {
            case 'seleccionar_cliente':
                $val = $this->validarCliente();
                if (!$val['status']) {
                    echo json_encode(ResponseHTTP::status400($val['message']));
                    break;
                }
                new CotizacionModel($this->data);
                echo json_encode(CotizacionModel::seleccionarCliente());
                break;

            case 'buscar_productos':
                $val = $this->validarBusqueda();
                if (!$val['status']) {
                    echo json_encode(ResponseHTTP::status400($val['message']));
                    break;
                }
                new CotizacionModel($this->data);
                echo json_encode(CotizacionModel::buscarProductos());
                break;

            case 'registrar_cantidades':
                $val = $this->validarProductos();
                if (!$val['status']) {
                    echo json_encode(ResponseHTTP::status400($val['message']));
                    break;
                }
                echo json_encode(CotizacionModel::registrarCantidades($this->data['productos']));
                break;

            case 'calcular_total':
                $val = $this->validarProductos();
                if (!$val['status']) {
                    echo json_encode(ResponseHTTP::status400($val['message']));
                    break;
                }
                echo json_encode(CotizacionModel::calcularTotal($this->data['productos']));
                break;

            case 'reservar_inventario':
                $val = $this->validarProductos();
                if (!$val['status']) {
                    echo json_encode(ResponseHTTP::status400($val['message']));
                    break;
                }
                echo json_encode(CotizacionModel::reservarInventario($this->data['productos']));
                break;

            default:
                echo json_encode(ResponseHTTP::status400("Acción no válida o no especificada."));
                break;
        }
    }
}