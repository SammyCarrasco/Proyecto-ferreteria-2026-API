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
           
            return ($query->rowCount() >0);
            
        } catch (\PDOException  $e) {
            //mandamos un error y especificamos la clase y el metodo ademas de el error correspondiente
            error_log("sql::verificarRegistro -> ".$e->getMessage());
            //retornamos el error correspondiente del server
            die(json_encode(responseHTTP::status500()));
            }
        }
    } 
