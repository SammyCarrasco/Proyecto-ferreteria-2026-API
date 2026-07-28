<?php

namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;

class cotizacionDetalleModel extends ConnectionDB {

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

        if (strpos($e->getMessage(), 'Existencias insuficientes') !== false) {
            return responseHTTP::status400('No hay suficiente stock disponible para este producto en el almacén seleccionado.');
        }

        return responseHTTP::status400('No se pudo agregar el producto. Verifica los datos e intenta de nuevo.');
    }
}

    
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

    final public static function consultarConDetalle($idCotizacion) {
        try {
            $con = self::getConnection();

            $stmt = $con->prepare(
                "SELECT c.id_cotizacion, c.fecha, c.total, c.estado, c.id_cliente, c.id_empleado,
                        cl.nombre AS cliente
                 FROM cotizaciones c
                 INNER JOIN clientes cl ON cl.id_cliente = c.id_cliente
                 WHERE c.id_cotizacion = :id"
            );
            $stmt->execute([':id' => $idCotizacion]);
            $cabecera = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$cabecera) {
                return responseHTTP::status400("No existe una cotización con ese id.");
            }

            $stmt2 = $con->prepare(
                "SELECT cd.id_detalle, cd.id_producto, cd.id_almacen, cd.cantidad, cd.precio_unitario,
                        (cd.cantidad * cd.precio_unitario) AS subtotal,
                        p.nombre AS producto
                 FROM cotizaciones_detalle cd
                 INNER JOIN productos p ON p.id_producto = cd.id_producto
                 WHERE cd.id_cotizacion = :id"
            );
            $stmt2->execute([':id' => $idCotizacion]);
            $cabecera['detalle'] = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

            return responseHTTP::status200($cabecera);
        } catch (\PDOException $e) {
            error_log("cotizacionDetalleModel::consultarConDetalle -> " . $e->getMessage());
            return responseHTTP::status400($e->getMessage());
        }
    } 

    
    final public static function listarPendientes() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare(
                "SELECT c.id_cotizacion, c.fecha, c.total, cl.nombre AS cliente
                 FROM cotizaciones c
                 INNER JOIN clientes cl ON cl.id_cliente = c.id_cliente
                 WHERE c.estado = 'Pendiente'
                 ORDER BY c.fecha DESC"
            );
            $stmt->execute();
            $lista = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return responseHTTP::status200($lista);
        } catch (\PDOException $e) {
            error_log("cotizacionDetalleModel::listarPendientes -> " . $e->getMessage());
            return responseHTTP::status400('No se pudo consultar las cotizaciones pendientes.');
        }
    }
}