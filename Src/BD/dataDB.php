
<?php
//este archivo prepara todos los datos necesarios para abrir mi conexion a la base de datos, y luego los envia a la clase ConnectionDB para abrir la conexion

use App\Config\ErrorLogs; //libreria para manejar los logs de errores
use App\Config\ResponseHTTP; //libreria para manejar las respuestas HTTP
use App\bd\ConnectionDB; //importamos la clase ConnectionDB para poder crear la conexion a la base de datos
use Dotenv\Dotenv; //importamos la libreria Dotenv para poder cargar las variables de entorno

ErrorLogs::activa_error_logs(); //activamos los logs de errores

$dotenv = Dotenv::createImmutable(dirname(__DIR__,2)); //creamos una instancia de la clase Dotenv y le pasamos la ruta donde se encuentra el archivo .env
$dotenv->load(); //cargamos las variables de entorno

$data = array(
    'IP' => $_ENV['IP'], //obtenemos el host de la base de datos desde el archivo .env
    'user' => $_ENV['USER'], //obtenemos el usuario de la base de datos desde el archivo .env
    'password' => $_ENV['PASSWORD'], //obtenemos la contraseña de la base de datos desde el archivo .env
    'DB' => $_ENV['DB'], //obtenemos el nombre de la base de datos desde el archivo .env
    'port' => $_ENV['PORT'] //obtenemos el puerto de la base de datos desde el archivo .env
);

//print_r($data); //imprimimos los datos de conexion a la base de datos para verificar que se cargaron correctamente

$host = 'mysql:host='.$data['IP'].';port='.$data['port'].';dbname='.$data['DB']; //preparamos la cadena de conexion a la base de datos

ConnectionDB::inicializar($host, $data['user'], $data['password']); //abrimos la conexion a la base de datos



