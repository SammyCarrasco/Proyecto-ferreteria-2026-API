<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class cotizacionDetalleModel extends connectionDB {

    // Atributos correspondientes a cotizaciones_detalle
    private static $id_detalle;
    private static $id_cotizacion;
    private static $id_producto;
    private static $id_almacen;
    private static $cantidad;
    private static $precio_unitario;

    public function __construct(array $data = []) {
        self::$id_detalle      = $data['id_detalle'] ?? '';
        self::$id_cotizacion   = $data['id_cotizacion'] ?? '';
        self::$id_producto     = $data['id_producto'] ?? '';
        self::$id_almacen      = $data['id_almacen'] ?? '';
        self::$cantidad        = $data['cantidad'] ?? '';
        self::$precio_unitario = $data['precio_unitario'] ?? '';
    }

    // Getters
    final public static function getIdDetalle()      { return self::$id_detalle; }
    final public static function getIdCotizacion()    { return self::$id_cotizacion; }
    final public static function getIdProducto()      { return self::$id_producto; }
    final public static function getIdAlmacen()       { return self::$id_almacen; }
    final public static function getCantidad()        { return self::$cantidad; }
    final public static function getPrecioUnitario()  { return self::$precio_unitario; }

    /**
     * Agregar un producto a la cotización (reserva stock)
     */
    final public static function agregarProducto() {
        try {
            $con = self::getConnection();
            $query = "CALL sp_registrar_cotizacion_detalle(:id_cotizacion, :id_producto, :id_almacen, :cantidad, :precio_unitario)";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':id_cotizacion'   => self::getIdCotizacion(),
                ':id_producto'     => self::getIdProducto(),
                ':id_almacen'      => self::getIdAlmacen(),
                ':cantidad'        => self::getCantidad(),
                ':precio_unitario' => self::getPrecioUnitario()
            ]);
            return responseHTTP::status200('Producto agregado a la cotización exitosamente!!!');
        } catch (\PDOException $e) {
            error_log("cotizacionDetalleModel::agregarProducto -> " . $e->getMessage());
            return responseHTTP::status400($e->getMessage());
        }
    }

    /**
     * Modificar la cantidad de un producto ya cotizado
     */
    final public static function modificarCantidad() {
        try {
            $con = self::getConnection();
            $query = "CALL sp_modificar_cantidad_cotizacion_detalle(:id_detalle, :cantidad)";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':id_detalle' => self::getIdDetalle(),
                ':cantidad'   => self::getCantidad()
            ]);
            return responseHTTP::status200('Cantidad actualizada exitosamente!!!');
        } catch (\PDOException $e) {
            error_log("cotizacionDetalleModel::modificarCantidad -> " . $e->getMessage());
            return responseHTTP::status400($e->getMessage());
        }
    }

    /**
     * Eliminar un producto de la cotización (libera reserva)
     */
    final public static function eliminarProducto($id_detalle) {
        try {
            $con = self::getConnection();
            $query = "CALL sp_eliminar_producto_cotizacion_detalle(:id_detalle)";
            $stmt = $con->prepare($query);
            $stmt->execute([
                ':id_detalle' => $id_detalle
            ]);
            return responseHTTP::status200('Producto eliminado de la cotización exitosamente!!!');
        } catch (\PDOException $e) {
            error_log("cotizacionDetalleModel::eliminarProducto -> " . $e->getMessage());
            return responseHTTP::status400($e->getMessage());
        }
    }
}