<?php

use App\Config\ResponseHTTP;
use App\Controllers\UserController;

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
//$procesado = $app->registrarUsuario('user'); //llamada al metodo post con la ruta al recurso
//$app->getUser("user/{$params[1]}/"); //traemos la info de un usuario en particular
//$app->patchPassword("user/password/"); //metodo para actualizar la contraseña
//$app->getAll('user'); //getAll trearemos todos los usuarios registrados


switch($method) {
    case 'post':
        $app->registrarUsuario("user"); //llamada al metodo post con la ruta al recurso
        break;
    case 'put':
        $app->actualizarUsuario("user"); //llamada al metodo put con la ruta al recurso
        break;
    case 'delete':
        $app->eliminarUsuario("user"); //metodo para eliminar un usuario en particular
        break;
    default:
        echo json_encode(ResponseHTTP::status405("Método no permitido"));
        break;
}
//$app->actualizarUsuario("user"); //llamada al metodo put con la ruta al recurso
//$app->eliminarUsuario("user"); //metodo para eliminar un usuario en particular
//$app->registrarUsuario("user"); //llamada al metodo post con la ruta al recurso
