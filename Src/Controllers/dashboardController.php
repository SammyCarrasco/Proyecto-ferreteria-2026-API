<?php

namespace App\Controllers;

use App\Models\dashboardModel;
use App\Config\responseHTTP;

class dashboardController
{

    private static $method;
    private static $route;
    private static $params;
    private static $data;
    private static $headers;

    public function __construct($method, $route, $params, $data, $headers)
    {
        self::$method = $method;
        self::$route = $route;
        self::$params = $params;
        self::$data = $data;
        self::$headers = $headers;
    }

    /**
     * Obtener información general del Dashboard
     */

    final public static function getDashboard($endpoint)
    {
        if(self::$method == 'get' && $endpoint == self::$route)
        {
            $dashboard = [
                // TOTAL DE VENTAS

                "ventas" => 
                    dashboardModel::totalVentas(),
                // TOTAL PRODUCTOS
                "productos" =>
                    dashboardModel::totalProductos(),
                // TOTAL CLIENTES
                "clientes" =>
                    dashboardModel::totalClientes(),
                // TOTAL COTIZACIONES
                "cotizaciones" =>
                    dashboardModel::totalCotizaciones(),
                // STOCK INVENTARIO
                "inventario" =>
                    dashboardModel::stockInventario(),

                // VENTAS POR MES
                "ventasMensuales" =>
                    dashboardModel::ventasMensuales(),
                // PRODUCTOS POR CATEGORIA

                "productosCategoria" =>
                    dashboardModel::productosCategoria()
            ];

            echo json_encode([
                "status" => 200,
                "message" =>
                "Información del dashboard obtenida correctamente",
                "data" =>
                $dashboard
            ]);

            exit;
        }

    }

}