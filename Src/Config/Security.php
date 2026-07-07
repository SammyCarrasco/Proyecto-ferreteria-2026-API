<?php
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
}