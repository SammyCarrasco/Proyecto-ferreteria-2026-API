<?php

namespace App\Controllers;

use App\Config\ResponseHTTP;
use App\Models\ReportesModel;

class ReportesController {

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

    // GET: /reportes/cotizaciones
    public function cotizacionesGenerales($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'cotizaciones') {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin    = $_GET['fecha_fin'] ?? null;

            $resultado = ReportesModel::obtenerCotizacionesGenerales($fechaInicio, $fechaFin);
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }

    // GET: /reportes/cotizaciones-cliente?id_cliente=1
    public function cotizacionesPorCliente($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'cotizaciones-cliente') {
            $idCliente   = $_GET['id_cliente'] ?? null;
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin    = $_GET['fecha_fin'] ?? null;

            if (empty($idCliente)) {
                echo json_encode(ResponseHTTP::status400('El ID del cliente es obligatorio'));
                exit;
            }

            $resultado = ReportesModel::obtenerCotizacionesPorCliente($idCliente, $fechaInicio, $fechaFin);
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }

    // GET: /reportes/facturas
    public function reportefacturas($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'facturas') {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin    = $_GET['fecha_fin'] ?? null;

            $resultado = ReportesModel::obtenerFacturas($fechaInicio, $fechaFin);
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }

    // GET: /reportes/isv
    public function reporteISV($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'isv') {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin    = $_GET['fecha_fin'] ?? null;

            $resultado = ReportesModel::obtenerISV($fechaInicio, $fechaFin);
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }

    // GET: /reportes/ganancias
    public function reporteGanancias($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'ganancias') {
            $fechaInicio = $_GET['fecha_inicio'] ?? null;
            $fechaFin    = $_GET['fecha_fin'] ?? null;

            $resultado = ReportesModel::obtenerGanancias($fechaInicio, $fechaFin);
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }

    // GET: /reportes/inversion
    public function reporteInversion($endpoint) {
        $endpoint = strtolower(trim($endpoint, '/'));
        $subruta  = self::$params[1] ?? '';

        if (self::$method === 'get' && self::$params[0] === $endpoint && $subruta === 'inversion') {
            $resultado = ReportesModel::obtenerInversion();
            echo json_encode(ResponseHTTP::status200($resultado));
            exit;
        }
    }
}