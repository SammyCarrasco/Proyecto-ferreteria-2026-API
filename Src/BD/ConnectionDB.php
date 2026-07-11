<?php
<<<<<<< HEAD
namespace App\bd;
use App\Config\ResponseHTTP; //libreria para manejar las respuestas HTTP
use PDO; //libreria para manejar la conexion a la base de datos
require __DIR__.'/dataDB.php'; //importamos el archivo dataDB.php para obtener los datos de conexion a la base de datos
class ConnectionDB {
    private static $host = ''; //variable para almacenar el host de la base de datos
    private static $user = ''; //variable para almacenar el usuario de la base de datos
    private static $pass = ''; //variable para almacenar la contraseña de la base de datos

    final public static function inicializar( $host,  $user,   $password) {
=======

namespace App\BD; //nombre de espacios con la carpeta donde esta ubicado este archivo
use App\config\responseHTTP;
use PDO; //usaremos el objeto PDO para interactuar con la BD
//requerimos la preparacion de este objeto incluyendo este archivo
require __DIR__ .'/dataDB.php'; // _DIR_ estamos en la misma carpeta

class ConnectionDB{
    private static $host = ''; //arreglo de datos (servidor, puerto, etc...)
    private static $user = '';
    private static $pass = '';

    final public static function connect($host, $user, $pass){
        //this or self?
        //self hace referencia a la clase para así mandar llamar funciones estáticas.
        //this hace referencia a un objeto ya instanciado para mandar llamar funciones de cualquier otro tipo
>>>>>>> 93b3d3d86a404d83fde1a1af30e9a3802fbd7ede
        self::$host = $host;
        self::$user = $user;
        self::$pass = $pass;
        return self::getConnection();
    }

<<<<<<< HEAD
    //creamos el metodo para abrir la conexion a la base de datos
    final public static function getConnection() {
        //echo "Conectando a la base de datos: ".self::$host." con el usuario: ".self::$user." y la contraseña: ".self::$pass;
        try{
            $opt = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];
            $pdo = new PDO(self::$host, self::$user, self::$pass, $opt);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            error_log('Conexión a la base de datos establecida correctamente.'); //registramos en el archivo de logs que la conexion fue exitosa
            return $pdo;

        }catch(\PDOException $e){
            //ResponseHTTP::error(500, 'Error al conectar a la base de datos: ' . $e->getMessage()); //enviamos un mensaje de error si no se puede conectar a la base de datos
            error_log('Error al conectar a la base de datos: ' . $e->getMessage()); //registramos el error en el archivo de logs
            die(json_encode(ResponseHTTP::status500())); //terminamos la ejecucion del script
=======
    //metodo que retorna la conexion
    final public static function getConnection(){
        try{
            //opciones de conexion
            $opt = [\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC];
            $pdo = new PDO(self::$host, self::$user, self::$pass, $opt);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            error_log("Conexión exitosa");
            return $pdo;
        }catch(\PDOException $e){
            error_log("Error en la conexión a la BD! ERROR: ".$e);
           return null;
>>>>>>> 93b3d3d86a404d83fde1a1af30e9a3802fbd7ede
        }
    }
    
}
?>
