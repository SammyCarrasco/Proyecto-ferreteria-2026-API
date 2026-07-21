<?php

use App\Controllers\CategoryController;

// 1. Capturamos la información real de la petición HTTP directamente del servidor
$method  = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE
$route   = $_GET['route'] ?? 'category';
$params  = $_GET;
$data    = json_decode(file_get_contents("php://input"), true) ?? []; // Captura el body JSON
$headers = getallheaders();

// 2. Instanciamos el controlador con los datos reales
$controller = new CategoryController($method, $route, $params, $data, $headers);
$controller->execute('category');