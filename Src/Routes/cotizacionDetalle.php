<?php

use App\Config\ResponseHTTP;
use App\Controllers\CotizacionDetalleController;

$method  = strtolower($_SERVER['REQUEST_METHOD']); // capturamos el metodo que se envia
$route   = $_GET['route']; // capturamos la ruta
$params  = explode('/', $route); // ej: cotizacionDetalle/5 -> ['cotizacionDetalle', '5']
$data    = json_decode(file_get_contents("php://input"), true); // body en POST/PUT
$headers = getallheaders();

$app = new CotizacionDetalleController($method, $route, $params, $data, $headers);


$app->agregarProducto("cotizacionDetalle");   
$app->modificarCantidad("cotizacionDetalle"); 
$app->eliminarProducto("cotizacionDetalle");  