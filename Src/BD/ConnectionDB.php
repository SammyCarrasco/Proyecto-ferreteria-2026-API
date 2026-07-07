<?php

namespace App\BD;

use App\Config\ResponseHTTP;
use PDO;

class ConnectionDB {
    private static $host = 'localhost';
    private static $user = 'root'; 
    private static $pass = '';

    final public static function inicializar($host, $user, $password) {
        self::$host = $host;
        self::$user = $user;
        self::$pass = $password;
    }

    // Método definitivo para obtener la conexión PDO
    final public static function getConnection() {
        try {
            $opt = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION
            ];
            
            // 🌟 PASO CLAVE: Creamos el formato DSN correcto incluyendo tu base de datos "ferreteria"
            // Si self::$host ya trae un valor como "localhost", aquí lo estructuramos bien.
            $dbName = 'ferreteria'; 
            $dsn = "mysql:host=" . self::$host . ";dbname=" . $dbName . ";charset=utf8mb4";
            
            // Pasamos la variable $dsn corregida en lugar de self::$host puro
            $pdo = new PDO($dsn, self::$user, self::$pass, $opt);
            
            error_log('Conexión a la base de datos establecida correctamente.');
            return $pdo;

        } catch (\PDOException $e) {
            error_log('Error al conectar a la base de datos: ' . $e->getMessage());
            
            // Si la clase ResponseHTTP existe, mandamos la respuesta formateada
            if (class_exists('App\Config\ResponseHTTP')) {
                die(json_encode(ResponseHTTP::status500()));
            } else {
                http_response_code(500);
                die(json_encode(["error" => "Error interno del servidor al conectar a la BD"]));
            }
        }
    }
}