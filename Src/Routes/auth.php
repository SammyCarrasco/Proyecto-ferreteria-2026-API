
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
    $app->getLogin("auth/{$params[1]}/{$params[2]}/"); //llamamos al metodo getlogin de la clase userController y le pasamos la ruta al recurso
    echo json_encode(responseHTTP::status404('Ruta no encontrada')); //si no se encuentra la ruta, devolvemos un error 404

use App\bd\ConnectionDB; //importamos la clase ConnectionDB para poder crear la conexion a la base de datos
ConnectionDB::getConnection(); //abrimos la conexion a la base de datos
?>