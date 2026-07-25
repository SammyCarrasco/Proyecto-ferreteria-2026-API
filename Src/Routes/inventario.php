<?php

use App\Controllers\InventarioController;

$method  = strtolower($_SERVER['REQUEST_METHOD']);
$route   = $_GET['route'] ?? 'inventario';
$params  = explode('/', $route);
$data    = json_decode(file_get_contents("php://input"), true) ?? [];
$headers = getallheaders();

$controller = new InventarioController($method,$route,$params,$data,$headers);
$controller->execute('inventario');
