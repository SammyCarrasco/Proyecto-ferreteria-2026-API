<?php
// src/bd/sql.php

namespace App\BD;

use App\Config\ResponseHTTP;

class sql extends ConnectionDB {

    // Método para verificar si existe un registro bajo ciertos parámetros
    final public static function verificarRegistro($sql, $params = []) {
        try {            
            // Solicitamos la conexión PDO activa
            $con   = self::getConnection();
            $query = $con->prepare($sql);
            
            // Ejecutamos pasando el array de parámetros
            $query->execute($params);
            
            // Retorna TRUE si encontró registros, FALSE si no
            return ($query->rowCount() > 0);
            
        } catch (\PDOException $e) {
            error_log("sql::verificarRegistro -> " . $e->getMessage());
            
            if (class_exists('App\Config\ResponseHTTP')) {
                die(json_encode(ResponseHTTP::status500()));
            } else {
                http_response_code(500);
                die(json_encode(["error" => "Error en la consulta SQL"]));
            }
        }
    } 
}