<?php 
require_once __DIR__ . '/../../vendor/autoload.php';

//Carga el .env e inicializa la configuración de la BD
require_once dirname(__DIR__) . '\BD\dataDB.php'; 

use App\BD\ConnectionDB;
use App\Controllers\AuthController;

ConnectionDB::getConnection();

$usuario = $_GET['user'] ?? '';
$password = $_GET['pass'] ?? '';

$auth = new AuthController();
$resultado = $auth->login($usuario, $password);

echo json_encode($resultado);