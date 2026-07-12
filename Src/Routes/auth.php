<?php


use App\Controllers\UserController;
use App\Config\responseHTTP;

//print_r($_GET['route']);
$method = strtolower($_SERVER['REQUEST_METHOD']); //capturamos el metodo que se envia
$route = $_GET['route']; //capturamos la ruta 
$params = explode('/', $route); // hacemos un explode de route ya que si nos envian user/email/clave tendriamos un array 
$data = json_decode(file_get_contents("php://input"),true); //contendra la data que enviemos por cualquier metodo excepto el get, array asociativo 
//Te permite capturar el texto JSON que llega en la petición HTTP y transformarlo en un array de PHP para poder leerlo, validarlo y usarlo en tu lógica de negocio o base de datos.
$headers = getallheaders(); //capturando todas las cabeceras que nos envian

$app = new UserController($method,$route,$params,$data,$headers); //instancia clase user controlador 
//$app-> registrarUsuario();//llamamos al metodo registrarUsuario de la clase UserController

$app->getLogin("auth"); //llamamos al metodo login de la clase UserController
echo json_encode(responseHTTP::status404());
/*

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
*/