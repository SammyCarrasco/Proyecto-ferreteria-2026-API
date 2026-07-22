<?php

use App\Config\ResponseHTTP;
use App\Controllers\productoController;

$method = strtolower($_SERVER['REQUEST_METHOD']); // Capturamos el método HTTP
$route = $_GET['route']; // Capturamos la ruta 
$params = explode('/', $route); 
$data = json_decode(file_get_contents("php://input"), true); // Payload JSON si aplica
$headers = getallheaders(); // Cabeceras

// Instanciamos el controlador de productos
$app = new productoController($method, $route, $params, $data, $headers); 

// Llamamos al método que despliega el catálogo de productos
$app->getCatalogo("productos");