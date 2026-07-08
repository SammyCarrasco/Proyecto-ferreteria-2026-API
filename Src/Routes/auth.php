<?php

// Ruta absoluta directa para XAMPP sin enredos de puntos
//require $_SERVER['DOCUMENT_ROOT'] . '/ProyectoFerreteriaAPI/Proyecto-ferreteria-2026-API/vendor/autoload.php';
require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Config\Security;
use App\BD\ConnectionDB;
use App\Controllers\UserController;
use App\Config\ResponseHTTP;

$method = strtolower($_SERVER['REQUEST_METHOD']); 
$route = $_GET['route']; 
$params = explode('/', $route);
$data = json_decode(file_get_contents("php://input"),true); 
$headers = getallheaders();

$app = new UserController($method,$route,$params,$data,$headers);
$app->getLogin("auth"); 

echo json_encode(responseHTTP::status404("Debes enviar correo y clave para la ruta"));


// Probando las funciones de seguridad
echo json_encode(Security::secretKey()) . "<br>";
echo json_encode(Security::createPassword("hola")) . "<br>";

// Validando contraseñas
$pass = Security::createPassword("hola");
if (Security::validatePassword("hola", $pass)) {
    echo json_encode("Contraseña correcta");
} else {
    echo json_encode("Contraseña incorrecta");
}

// Prueba rápida de conexión
$BD = require __DIR__ . '/../BD/dataDB.php';
echo json_encode("¡Conexión a la base de datos exitosa!");

App\BD\ConnectionDB::getConnection();