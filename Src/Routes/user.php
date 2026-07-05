<?php
require_once __DIR__ . "/../../vendor/autoload.php"; // carga dependencias de Composer
require_once __DIR__ . "/../Config/Security.php";
require_once __DIR__ . "/../Config/ResponseHTTP.php";

use App\Config\Security;
use App\Config\ResponseHTTP;

// Obtener la clave secreta desde .env
$key = Security::secretKey();

// Validar el token JWT desde el header Authorization
$jwt = Security::validateTokenExt($key);

// Si la validación pasa, devolver datos protegidos
echo json_encode(ResponseHTTP::status200("Acceso concedido"));
echo json_encode([
    "data" => Security::getDataJwt()
]);
