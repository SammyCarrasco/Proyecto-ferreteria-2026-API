<?php
use App\Controllers\dashboardController;
$method = strtolower($_SERVER['REQUEST_METHOD']); 
$route = $_GET['route']; 
$params = explode('/', $route); 
$data = json_decode(file_get_contents("php://input"), true); 
$headers = getallheaders(); 
// Instanciamos el controlador Dashboard
$app = new dashboardController(
    $method,
    $route,
    $params,
    $data,
    $headers
);
$app->getDashboard("dashboard");