<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class ventaModel extends connectionDB {

    
    private static $id_cotizacion;
    private static $id_empleado;

    public function __construct(array $data = []) {
        self::$id_cotizacion = $data['id_cotizacion'] ?? '';
        self::$id_empleado   = $data['id_empleado'] ?? '';
    }

    // Getters
    final public static function getIdCotizacion() { return self::$id_cotizacion; }
    final public static function getIdEmpleado()   { return self::$id_empleado; }

    
    final public static function procesarVenta() {
        try {
            $con = self::getConnection();
            $query = "CALL sp_procesar_venta_factura(:id_cotizacion, :id_empleado)";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':id_cotizacion' => self::getIdCotizacion(),
                ':id_empleado'   => self::getIdEmpleado()
            ]);
            $stmt->closeCursor();

            // Traemos los datos de la venta recien generada
            $stmt2 = $con->prepare(
                "SELECT id_venta, nro_factura, fecha, id_cotizacion, id_empleado, subtotal, isv, total
                 FROM ventas WHERE id_cotizacion = :id_cotizacion"
            );
            $stmt2->execute([':id_cotizacion' => self::getIdCotizacion()]);
            $venta = $stmt2->fetch(\PDO::FETCH_ASSOC);

            $respuesta = responseHTTP::status200('Venta registrada exitosamente!!!');
            $respuesta['data'] = $venta;
            return $respuesta;
        } catch (\PDOException $e) {
            error_log("ventaModel::procesarVenta -> " . $e->getMessage());

            if (strpos($e->getMessage(), 'id_empleado') !== false) {
                return responseHTTP::status400('El ID de empleado ingresado no existe.');
            }
            if (strpos($e->getMessage(), 'id_cotizacion') !== false) {
                return responseHTTP::status400('El ID de cotización ingresado no existe.');
            }

            return responseHTTP::status400('No se pudo procesar la venta. Verifica los datos ingresados.');
        }
    }
}