<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Config\ResponseHTTP;

class CategoryController {
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

    final public function execute($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        if ($endpoint !== self::$route) return;

        // POST: Crear Categoría
        if (self::$method === 'post') {
            if (empty(self::$data['nombre']) || empty(self::$data['descripcion'])) {
                echo json_encode(ResponseHTTP::status400('El nombre y descripción de la categoría es obligatorio'));
                exit;
            }
            new CategoryModel(self::$data);
            echo json_encode(CategoryModel::post());
            exit;
        }

        // GET: Consultar Categorías
        if (self::$method === 'get') {
            echo json_encode(CategoryModel::get());
            exit;
        }

        // PUT: Actualizar Categoría
        if (self::$method === 'put') {
            if (empty(self::$data['id_categoria']) || empty(self::$data['nombre'])) {
                echo json_encode(ResponseHTTP::status400('ID y Nombre son requeridos para actualizar'));
                exit;
            }
            new CategoryModel(self::$data);
            echo json_encode(CategoryModel::put());
            exit;
        }

        // DELETE: Eliminar Categoría
        if (self::$method === 'delete') {
            if (empty(self::$data['id_categoria'])) {
                echo json_encode(ResponseHTTP::status400('El ID de la categoría es requerido'));
                exit;
            }
            new CategoryModel(self::$data);
            echo json_encode(CategoryModel::delete());
            exit;
        }
    }
}