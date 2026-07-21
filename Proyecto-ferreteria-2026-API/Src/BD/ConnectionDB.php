<?php
namespace App\BD;
use App\Config\ResponseHTTP; //libreria para manejar las respuestas HTTP
use PDO; //libreria para manejar la conexion a la base de datos
require __DIR__.'/dataDB.php'; //importamos el archivo dataDB.php para obtener los datos de conexion a la base de datos
class ConnectionDB {
    private static $host = ''; //variable para almacenar el host de la base de datos
    private static $user = ''; //variable para almacenar el usuario de la base de datos
    private static $pass = ''; //variable para almacenar la contraseña de la base de datos

    final public static function inicializar( $host,  $user,   $pass) {
        self::$host = $host;
        self::$user = $user;
        self::$pass = $pass;
    }

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
        }
    }
    
}
?>
