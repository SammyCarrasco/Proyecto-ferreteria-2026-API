<?php
require_once __DIR__ . "/../../vendor/autoload.php"; 
require_once __DIR__ . "/../Config/Security.php";
require_once __DIR__ . "/../Config/ResponseHTTP.php";

use App\Config\Security;
use App\Config\ResponseHTTP;

// Recibir datos por POST
$data = json_decode(file_get_contents("php://input"), true);
$user = $data["user"] ?? "";
$password = $data["password"] ?? "";

// Aquí se deberia validar contra la BD (ejemplo con PDO)
// Por ahora se simula:
if ($user === "admin" && $password === "12345") {
    $token = Security::createTokenJwt(Security::secretKey(), ["user" => $user]);
    echo json_encode(ResponseHTTP::status200("Login exitoso"));
    echo json_encode(["token" => $token]);
} else {
    echo json_encode(ResponseHTTP::status401("Credenciales inválidas"));
}
