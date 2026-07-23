<?php
// Src/Routes/Cotizacion.php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../Controllers/CotizacionController.php';

// Credenciales para la conexión a MySQL en XAMPP
$host = "localhost";
$db_name = "ferreteria"; 
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error de conexión con la base de datos: " . $e->getMessage()
    ]);
    exit;
}

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido. Utiliza el método POST."
    ]);
    exit;
}

$controller = new CotizacionController($pdo);
$accion = $_GET['accion'] ?? 'seleccionar_cliente';

// Mapeo dinámico de acciones a métodos del controlador
$mapaAcciones = [
    'seleccionar_cliente' => 'seleccionarCliente',
    'buscar_productos'    => 'buscarProductos',
    'validar_cantidad'    => 'validarCantidad',
    'calcular_total'      => 'calcularTotal',
    'reservar_inventario' => 'reservarInventario'
];

// Ejecutar la acción si existe en el mapa; de lo contrario, ejecutar por defecto seleccionarCliente
$metodoController = $mapaAcciones[$accion] ?? 'seleccionarCliente';
$controller->$metodoController();
?>