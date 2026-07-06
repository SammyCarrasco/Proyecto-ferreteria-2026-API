<?php

//Este archivo nos permitite preparar los datos para nuestra conexion

//referenciamos a nuestros objetos segun el nombre de espacios
use App\Config\errorlogs;
use App\Config\ResponseHTTP;
use App\BD\ConnectionDB;
use Dotenv\Dotenv;

//activamos la configuración de los errores
errorlogs::activa_error_logs();

/* cargamos nuestras variables de entorno de nuestra conexion a BD*/
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

//definimos un arreglos para simplificar y pasar la cadena de caracteres necesaria para abrir la conexion PDO
$data = [
    "user" => $_ENV['USER'] ?? 'root',
    "password" => $_ENV['PASSWORD'] ?? '',
    "BD" => $_ENV['BD'] ?? 'ferreteria',
    "IP" => $_ENV['IP'] ?? '127.0.0.1',
    "port" => $_ENV['PORT'] ?? '3306'
];

/* conectamos a la base de datos llamando al metodo de la clase que retorna PDO*/
$host = 'mysql:host='.$data['IP'].';'.'port='.$data['port'].';'.'dbname='.$data['BD']; //cadena necesaria

//inicializamos el objeto conexión
return ConnectionDB::connect($host, $data['user'], $data['password']);