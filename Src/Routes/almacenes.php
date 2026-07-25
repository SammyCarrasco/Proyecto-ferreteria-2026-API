<?php

use App\Controllers\AlmacenController;

$method  = strtolower($_SERVER['REQUEST_METHOD']);
$route   = $_GET['route'] ?? 'almacenes';
$params  = explode('/', $route);
$data    = json_decode(file_get_contents("php://input"), true) ?? [];
$headers = getallheaders();

$controller = new AlmacenController($method,$route,$params,$data,$headers);
$controller->execute('almacenes');
