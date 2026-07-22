<?php
namespace App\Controllers;

use App\Models\AdminProductoModel;
use App\Config\ResponseHTTP;

class AdminProductoController {
    private static $method;
    private static $route;
    private static $params;
    private static $data;
    private static $headers;

    public function __construct($method, $route, $params, $data, $headers) {
        self::$method = strtolower($method);
        self::$route = strtolower(trim($route, '/'));
        self::$params = $params;
        self::$data = $data;
        self::$headers = $headers;
    }

    final public function execute($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        if ($endpoint !== self::$route) return;

        // POST: Registrar Producto
        if (self::$method === 'post') {
            if (empty(self::$data['codigo']) || empty(self::$data['nombre'])
                || empty(self::$data['id_categoria']) || empty(self::$data['id_unidad'])) {
                echo json_encode(ResponseHTTP::status400('Código, nombre, categoría y unidad de medida son obligatorios'));
                exit;
            }
            if (!is_numeric(self::$data['precio_compra'] ?? null) || !is_numeric(self::$data['precio_venta'] ?? null)) {
                echo json_encode(ResponseHTTP::status400('Los precios de compra y venta deben ser numéricos'));
                exit;
            }
            new AdminProductoModel(self::$data);
            echo json_encode(AdminProductoModel::post());
            exit;
        }

        // GET: Consultar todos los productos  o uno solo, con ?id=
        if (self::$method === 'get') {
            if (!empty(self::$params['id'])) {
                new AdminProductoModel(['id_producto' => self::$params['id']]);
                echo json_encode(AdminProductoModel::getOne());
                exit;
            }
            echo json_encode(AdminProductoModel::get());
            exit;
        }

        // PUT: Actualizar Producto
        if (self::$method === 'put') {
            if (empty(self::$data['id_producto']) || empty(self::$data['codigo']) || empty(self::$data['nombre'])) {
                echo json_encode(ResponseHTTP::status400('ID, código y nombre son requeridos para actualizar'));
                exit;
            }
            new AdminProductoModel(self::$data);
            echo json_encode(AdminProductoModel::put());
            exit;
        }

        // DELETE: Eliminar Producto
        if (self::$method === 'delete') {
            if (empty(self::$data['id_producto'])) {
                echo json_encode(ResponseHTTP::status400('El ID del producto es requerido'));
                exit;
            }
            new AdminProductoModel(self::$data);
            echo json_encode(AdminProductoModel::delete());
            exit;
        }
    }
}


