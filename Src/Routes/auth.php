<<<<<<< HEAD

<?php
  
  use App\controllers\userController; //importamos la clase userController para poder usarla en este archivo
    use App\Config\responseHTTP; //importamos la clase responseHTTP para poder usarla en este archivo

    $method = strtolower($_SERVER['REQUEST_METHOD']); //obtenemos el metodo de la peticion
    $route = $_GET['route']; //obtenemos la ruta de la peticion
    $params = explode('/', $route); //separamos la ruta en un array
    $data = json_decode(file_get_contents('php://input'), true); //obtenemos los datos de la peticion
    $headers = getallheaders(); //obtenemos los headers de la peticion
   
    $app = new userController($method, $route, $params, $data, $headers); //creamos una instancia de la clase userController y le pasamos los parametros de la peticion
    //llamada al metodo getlogin con la ruta al recurso
    //recordar que $params [0] contiene la ruta
    $app->getLogin("auth/{$params[1]}/{$params[2]}"); //llamamos al metodo getlogin de la clase userController y le pasamos la ruta al recurso
    echo json_encode(responseHTTP::status404('Ruta no encontrada')); //si no se encuentra la ruta, devolvemos un error 404

use App\bd\ConnectionDB; //importamos la clase ConnectionDB para poder crear la conexion a la base de datos
ConnectionDB::getConnection(); //abrimos la conexion a la base de datos
?>
=======
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
>>>>>>> 93b3d3d86a404d83fde1a1af30e9a3802fbd7ede
