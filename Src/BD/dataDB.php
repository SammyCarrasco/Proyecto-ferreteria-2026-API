<?php

use App\Config\ErrorLogs;
use App\BD\ConnectionDB;
use Dotenv\Dotenv;

// Activamos los logs de errores 
if (class_exists('App\Config\ErrorLogs')) {
    ErrorLogs::activa_error_logs();
}

// Cargamos las variables de entorno 
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

// Estructuramos los datos en el array
$data = array(
    'IP'       => $_ENV['IP'],
    'user'     => $_ENV['USER'],
    'password' => $_ENV['PASSWORD'],
    'DB'       => $_ENV['DB'],
    'port'     => $_ENV['PORT']
);

// Preparamos la cadena de conexión PDO para MySQL
$host = 'mysql:host=' . $data['IP'] . ';port=' . $data['port'] . ';dbname=' . $data['DB'] . ';charset=utf8mb4';

// Inicializamos los parámetros en la clase 
ConnectionDB::inicializar($host, $data['user'], $data['password']);