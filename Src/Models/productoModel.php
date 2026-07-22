<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class productoModel extends connectionDB {

    /**
     * Método para obtener el catálogo de productos (Caso de Uso 10)
     */
    final public static function getCatalogo() {
        try {
            $con = self::getConnection();
            $query = "CALL sp_obtener_catalogo_productos()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $res;
        } catch (\PDOException $e) {
            error_log("productoModel::getCatalogo -> " . $e->getMessage());
            return [];
        }
    }
}