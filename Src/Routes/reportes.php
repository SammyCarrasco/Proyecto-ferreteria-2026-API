<?php

use App\Controllers\ReportesController;

$method  = strtolower($_SERVER['REQUEST_METHOD']);
$route   = $_GET['route'] ?? ''; 
$params  = explode('/', $route); 
$data    = json_decode(file_get_contents("php://input"), true) ?? []; 
$headers = getallheaders();

$app = new ReportesController($method, $route, $params, $data, $headers);

$app->cotizacionesGenerales('reportes');
$app->cotizacionesPorCliente('reportes');
$app->reportefacturas('reportes');
$app->reporteISV('reportes');
$app->reporteGanancias('reportes');
$app->reporteInversion('reportes');