<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class ventaModel extends connectionDB {

    // Atributos correspondientes a ventas
    private static $id_cotizacion;
    private static $id_empleado;

    public function __construct(array $data = []) {
        self::$id_cotizacion = $data['id_cotizacion'] ?? '';
        self::$id_empleado   = $data['id_empleado'] ?? '';
    }

    // Getters
    final public static function getIdCotizacion() { return self::$id_cotizacion; }
    final public static function getIdEmpleado()   { return self::$id_empleado; }

    /**
     * Procesar la venta: convierte una cotización Pendiente en una factura
     */
    final public static function procesarVenta() {
        try {
            $con = self::getConnection();
            $query = "CALL sp_procesar_venta_factura(:id_cotizacion, :id_empleado)";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':id_cotizacion' => self::getIdCotizacion(),
                ':id_empleado'   => self::getIdEmpleado()
            ]);
            return responseHTTP::status200('Venta registrada exitosamente!!!');
        } catch (\PDOException $e) {
            error_log("ventaModel::procesarVenta -> " . $e->getMessage());
            return responseHTTP::status400($e->getMessage());
        }
    }
}