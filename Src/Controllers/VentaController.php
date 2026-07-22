<?php

namespace App\Controllers;

use App\Models\ventaModel;
use App\Config\ResponseHTTP;

class VentaController {
    private static $method;
    private static $route;
    private static $params;
    private static $data;
    private static $headers;

    public function __construct($method, $route, $params, $data, $headers) {
        self::$method  = strtolower($method);
        self::$route   = strtolower(trim($route, '/'));
        self::$params  = $params;
        self::$data    = $data;
        self::$headers = $headers;
    }

    /* PROCESAR VENTA / FACTURAR COTIZACIÓN (POST) */
    final public function procesarVenta($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $base = strtolower(self::$params[0] ?? '');

        if (self::$method === 'post' && $endpoint === $base) {

            if (empty(self::$data['id_cotizacion']) || empty(self::$data['id_empleado'])) {
                echo json_encode(ResponseHTTP::status400('Los campos id_cotizacion e id_empleado son requeridos.'));
                exit;
            } else if (!is_numeric(self::$data['id_cotizacion'])) {
                echo json_encode(ResponseHTTP::status400('El id_cotizacion debe contener solo números.'));
                exit;
            } else if (!is_numeric(self::$data['id_empleado'])) {
                echo json_encode(ResponseHTTP::status400('El id_empleado debe contener solo números.'));
                exit;
            }

            new ventaModel(self::$data);
            echo json_encode(ventaModel::procesarVenta());
            exit;
        }
    }
}