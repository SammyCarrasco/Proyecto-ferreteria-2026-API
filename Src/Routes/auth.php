<?php

// Ruta absoluta directa para XAMPP sin enredos de puntos
require $_SERVER['DOCUMENT_ROOT'] . '/ProyectoFerreteriaAPI/Proyecto-ferreteria-2026-API/vendor/autoload.php';

use App\Config\Security;
use App\BD\ConnectionDB;

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