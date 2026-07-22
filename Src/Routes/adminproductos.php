<?php
use App\Controllers\AdminProductoController;

$method  = $_SERVER['REQUEST_METHOD'];
$route   = $_GET['route'] ?? 'adminproductos';
$params  = $_GET;
$data    = json_decode(file_get_contents("php://input"), true) ?? [];
$headers = getallheaders();
 
$controller = new AdminProductoController($method, $route, $params, $data, $headers);
$controller->execute('adminproductos');