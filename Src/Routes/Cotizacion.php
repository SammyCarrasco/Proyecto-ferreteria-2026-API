<?php

namespace App\Routes;

use App\Controllers\CotizacionController;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method  = $_SERVER['REQUEST_METHOD'];
$route   = $_GET['route'] ?? 'cotizacion';
$params  = $_GET;
$data    = json_decode(file_get_contents("php://input"), true) ?? [];
$headers = getallheaders();

$controller = new CotizacionController($method, $route, $params, $data, $headers);
$controller->execute($route);