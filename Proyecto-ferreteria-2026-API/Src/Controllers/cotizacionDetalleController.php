<?php

namespace App\Controllers;

use App\Models\cotizacionDetalleModel;
use App\Config\ResponseHTTP;

class CotizacionDetalleController {
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

    /* AGREGAR PRODUCTO A LA COTIZACIÓN (POST) */
    final public function agregarProducto($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $base = strtolower(self::$params[0] ?? '');

        if (self::$method === 'post' && $endpoint === $base) {

            if (empty(self::$data['id_cotizacion']) || empty(self::$data['id_producto']) ||
                empty(self::$data['id_almacen']) || empty(self::$data['cantidad']) ||
                empty(self::$data['precio_unitario'])) {
                echo json_encode(ResponseHTTP::status400('Todos los campos son requeridos: id_cotizacion, id_producto, id_almacen, cantidad, precio_unitario.'));
                exit;
            } else if (!is_numeric(self::$data['cantidad']) || self::$data['cantidad'] <= 0) {
                echo json_encode(ResponseHTTP::status400('La cantidad debe ser un número mayor a cero.'));
                exit;
            } else if (!is_numeric(self::$data['precio_unitario']) || self::$data['precio_unitario'] <= 0) {
                echo json_encode(ResponseHTTP::status400('El precio unitario debe ser un número mayor a cero.'));
                exit;
            }

            new cotizacionDetalleModel(self::$data);
            echo json_encode(cotizacionDetalleModel::agregarProducto());
            exit;
        }
    }

    /* MODIFICAR CANTIDAD DE UN PRODUCTO COTIZADO (PUT) */
    final public function modificarCantidad($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $base = strtolower(self::$params[0] ?? '');

        if (self::$method === 'put' && $endpoint === $base) {

            if (empty(self::$data['id_detalle']) || empty(self::$data['cantidad'])) {
                echo json_encode(ResponseHTTP::status400('Los campos id_detalle y cantidad son requeridos.'));
                exit;
            } else if (!is_numeric(self::$data['cantidad']) || self::$data['cantidad'] <= 0) {
                echo json_encode(ResponseHTTP::status400('La cantidad debe ser un número mayor a cero.'));
                exit;
            }

            new cotizacionDetalleModel(self::$data);
            echo json_encode(cotizacionDetalleModel::modificarCantidad());
            exit;
        }
    }

    /*ELIMINAR PRODUCTO DE LA COTIZACIÓN (DELETE) */
    final public function eliminarProducto($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $base = strtolower(self::$params[0] ?? '');

        if (self::$method === 'delete' && $endpoint === $base) {

            $id_detalle = self::$params[1] ?? null;

            if (empty($id_detalle)) {
                echo json_encode(ResponseHTTP::status400('Debe enviar el id_detalle en la URL a eliminar.'));
                exit;
            } else if (!is_numeric($id_detalle)) {
                echo json_encode(ResponseHTTP::status400('El id_detalle debe contener solo números.'));
                exit;
            }

            echo json_encode(cotizacionDetalleModel::eliminarProducto($id_detalle));
            exit;
        }
    }
}