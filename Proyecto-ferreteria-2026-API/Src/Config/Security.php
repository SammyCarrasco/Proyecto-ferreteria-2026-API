<?php

namespace App\Config;
use Dotenv\Dotenv; //variables de entorno 
use Firebase\JWT\JWT; //libreria para crear JWT
use Firebase\JWT\Key; //libreria para validar JWT
use App\Config\ResponseHttp; //libreria para manejar las respuestas HTTP
class Security {
    private static $jwt_data; //Propiedad para guardar los datos decodificados del JWT

    /*METODO para Acceder a la secret key para crear el JWT*/
    final public static function secretkey() {
       //cargamos las variables de entorno en el archivo .env
        $dotenv = Dotenv::createImmutable(dirname(__DIR__,2)); //nuestras variables de entorno estaran en la raiz
                    // del proyecto (el numero dos son los niveles a lo externo, para llegar al directorio raiz)
        $dotenv->load(); //cargando las variables de entorno
        return $_ENV['SECRET_KEY']; //le doy un nombre a nuestra variable de entorno y la retornamos
        //en realidad lo que sucede aqui es por medio de la superglobal $_ENV creamos una variable de entorno
    
    }

    /*METODO para Encriptar la contraseña del usuario*/
    final public static function createPassword($pass)
    {
        $pass = password_hash($pass,PASSWORD_DEFAULT); //metodo para encriptar mediante hash
        //recibe 2 parametros el primero el la cadena (pass) y el segundo es el metodo de encriptación (por defecto BCRIPT)
        return $pass;
    }

    /*Metodo para Validar que las contraseñas coincidan o sean iguales*/
    final public static function validatePassword($pw,$pwh)
    {
        if (password_verify($pw,$pwh)) {
            return  TRUE;
        } else {
           return  FALSE;
        }
    }

    /*MEtodo para crear JWT*/
    /*1. Header: contiene el tipo de token y el algoritmo de encriptacion
      2. Payload: contiene la data que queremos encriptar
      3. Signature: es la firma del JWT, se crea a partir de la clave secreta y el payload*/
 
    /*PARAM: 1.	SECRET_KEY <=> firma de JWT
             2.	ARRAY con la data que queremos encriptar*/

    final public static function createTokenJwt(string $key , array $data)
    {
        /*incorporamos al payload el tiempo de creacion del JWT, el tiempo de expiracion y la data que queremos encriptar*/
        $payload = array ( //Cuerpo del JWT
            "iat" => time(),  //clave que almacena el tiempo en el que creamos el JWT
            "exp" => time() + (60*60*6), //clave que almacena el tiempo actual en segundos que expira el JWT
            //si solo colocamos 10 entonces expirara en 10 segundos 60 seg*60 min*1 hr
            "data" => $data //clave que almacena la data encriptada
        );
        //print_r($payload);

        //creamos el JWT recibe varios parametros pero nos interesa el payload y la key en el metodo encode de JWT
        $jwt = JWT::encode($payload,$key,'HS256'); //param1: payload, param2: clave, param3: metodo por defecto de encriptacion
        //print_r($jwt);
        return $jwt;
    }

    /*Validamos que el JWT sea correcto*/
    //recibimos dos parametros uno es un array y otro es la KEY para decifrar nuestro JWT
    final public static function validateTokenJwt($token, $key)
    {
        
        //usaremos el metodo getallheader() el que Recupera todas las cabeceras de petición HTTP
        //buscaremos la cabecera Autorization, sino existe la detiene y manda un mensaje de error
        //thunterClient autorization 
        //postman Autorization
        if (!isset(getallheaders()['Authorization'])) {
            //echo "El token de acceso en requerido";
            die(json_encode(ResponseHttp::status400("Para proceder el token de acceso es requerido!")));            
            exit;
        }
        try {
            //recibimos el token de acceso y creamos el array 
            //se veria mas o menos asi 
            // $token = "Bearer parte1.parte2.parte3"; posicion 0 y posicion 1
            
            $jwt = explode(" " ,getallheaders()['Authorization']);
            //print_r($jwt[0]); //imprimimos el token de acceso
            $data = JWT::decode($jwt[1],new Key($key, 'HS256'));
             //param1: token, param2: clave, param3: algoritmos permitidos
            //print_r($data); //imprimimos el token decodificado
                //print_r($data);    
                //necesitamos crear un array asociativo para poder retornarlo y que sea mas facil recorrerlo
            //1. definimos el atributo 
            //private static $jwt_data;//Propiedad para guardar los datos decodificados del JWT 

            self::$jwt_data = $data; //le pasamos el jwt decodificado y lo retornamos
            return self::$jwt_data; //retorna el JWT decodificado completo (iat, exp, data)
            exit;
        } catch (\Exception $e) {
            error_log('Token invalido o expiro'. $e);
            die(json_encode(ResponseHttp::status401('Token invalido o ha expirado'))); //funcion que manda un mj y termina ejecucion 
        }
    }

    /*Devolver los datos del JWT decodificados en un array asociativo*/
    final public static function getDataJwt()
    {
        $jwt_decoded_array = json_decode(json_encode(self::$jwt_data),true);
        return $jwt_decoded_array['data'];
        //return self::$jwt_data; //retorna solo la data del JWT decodificado
        exit;
    }
}
/*
namespace App\Config;
use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Config\ResponseHTTP;


class Security {
    private static $jwt_data;

    // Obtener la clave secreta desde .env
    final public static function secretKey() {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }

    // Crear contraseña encriptada
    final public static function createPassword(string $pass) {
        return password_hash($pass, PASSWORD_DEFAULT);
    }

    // Validar contraseña
    final public static function validatePassword(string $pw, string $pwh) {
        return password_verify($pw, $pwh);
    }

    // Aquí luego añadiremos createTokenJWT  validateTokenJWT
    final public static function createTokenJwt(string $key, array $data) {
    $payload = [
        "iat" => time(), // fecha de creación
        "exp" => time() + (60*60*6), // expira en 6 horas
        "data" => $data // información que quieres encriptar
    ];
    return JWT::encode($payload, $key, 'HS256');
    }

    //validateTokenJWT
    final public static function validateTokenExt(string $key) {
        if (!isset(getallheaders()['Authorization'])) {
            die(json_encode(ResponseHTTP::status401('Falta token')));
        }
        try {
            $jwt = explode(' ', getallheaders()['Authorization']);
            $jwt_data = JWT::decode($jwt[1], new Key($key, 'HS256'));
            self::$jwt_data = $jwt_data;
            return $jwt_data;
        } catch (\Exception $e) {
            error_log('Token inválido o expiró: ' . $e);
            die(json_encode(ResponseHTTP::status401('Token inválido o ha expirado')));
        }
    }


    final public static function getDataJwt() 
    {
        $jwt_decoded_array = json_decode(json_encode(self::$jwt_data), true);
        return $jwt_decoded_array['data'];
        exit;
    }
}*/