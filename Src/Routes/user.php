<?php
require_once __DIR__ . "/../../vendor/autoload.php"; // carga dependencias de Composer
require_once __DIR__ . "/../Config/Security.php";
require_once __DIR__ . "/../Config/ResponseHTTP.php";

use App\Config\Security;
use App\Config\ResponseHTTP;
use App\Controllers\UserController;

// Obtener la clave secreta desde .env
$key = Security::secretKey();

// Validar el token JWT desde el header Authorization
$jwt = Security::validateTokenExt($key);

// Si la validación pasa, devolver datos protegidos
//echo json_encode(ResponseHTTP::status200("Acceso concedido"));
/*echo json_encode([
    "data" => Security::getDataJwt()
]);*/

//////////////////////////////////////////////

$method = strtolower($_SERVER['REQUEST_METHOD']); //capturamos el metodo que se envia
$route = $_GET['route']; //capturamos la ruta 
$params = explode('/', $route); // hacemos un explode de route ya que si nos envian user/email/clave tendriamos un array 
$data = json_decode(file_get_contents("php://input"),true); //contendra la data que enviemos por cualquier metodo excepto el get, array asociativo 
//Te permite capturar el texto JSON que llega en la petición HTTP y transformarlo en un array de PHP para poder leerlo, validarlo y usarlo en tu lógica de negocio o base de datos.
$headers = getallheaders(); //capturando todas las cabeceras que nos envian

//print_r($data);



//print_r($route);
$app = new UserController($method,$route,$params,$data,$headers); //instancia clase user controlador 
//$app->getAll('user/'); //getAll trearemos todos los usuarios registrados
$app->post('user'); //llamada al metodo post con la ruta al recurso
//$app->getUser("user/{$params[1]}/"); //traemos la info de un usuario en particular
//$app->patchPassword("user/password/"); //metodo para actualizar la contraseña



echo json_encode(responseHTTP::status404("Ruta no encontrada")); //imprimamos un error en caso de no encuentre la ruta
