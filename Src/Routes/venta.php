<?php

use App\Config\ResponseHTTP;
use App\Controllers\VentaController;

$method  = strtolower($_SERVER['REQUEST_METHOD']); // capturamos el metodo que se envia
$route   = $_GET['route']; // capturamos la ruta
$params  = explode('/', $route); // ej: venta -> ['venta']
$data    = json_decode(file_get_contents("php://input"), true); // body en POST
$headers = getallheaders();

$app = new VentaController($method, $route, $params, $data, $headers);

$app->procesarVenta("venta"); // POST -> factura una cotización (calcula ISV, descuenta stock, cambia estado)