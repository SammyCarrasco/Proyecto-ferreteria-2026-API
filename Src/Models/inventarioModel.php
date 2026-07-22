<?php

namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;
use PDO;

class InventarioModel extends ConnectionDB {
    private static $id_producto;
    private static $id_almacen;
    private static $stock_disponible;
    private static $stock_reservado;

    public function __construct(array $data = []) {
        self::$id_producto      = $data['id_producto'] ?? null;
        self::$id_almacen       = $data['id_almacen'] ?? null;
        self::$stock_disponible = $data['stock_disponible'] ?? 0;
        self::$stock_reservado  = $data['stock_reservado'] ?? 0;
    }

    final public static function asociarProducto() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL AsociarProductoAlmacen(:id_producto, :id_almacen, :stock_disponible, :stock_reservado)");
            $stmt->execute([
                ':id_producto' => self::$id_producto,
                ':id_almacen' => self::$id_almacen,
                ':stock_disponible' => self::$stock_disponible,
                ':stock_reservado' => self::$stock_reservado
            ]);
            return ResponseHTTP::status200("Producto asociado al almacén exitosamente");
        } catch (\PDOException $e) {
            error_log("InventarioModel::asociarProducto ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    final public static function getStock() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ConsultarInventario()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("InventarioModel::getStock ".$e->getMessage());
            return [];
        }
    }

    final public static function actualizarStock() {
        if (self::$stock_reservado > self::$stock_disponible) {
            return ResponseHTTP::status400("El stock reservado no puede superar al disponible");
        }
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ActualizarInventario(:id_producto, :id_almacen, :stock_disponible, :stock_reservado)");
            $stmt->execute([
                ':id_producto' => self::$id_producto,
                ':id_almacen' => self::$id_almacen,
                ':stock_disponible' => self::$stock_disponible,
                ':stock_reservado' => self::$stock_reservado
            ]);
            return ResponseHTTP::status200("Inventario actualizado correctamente");
        } catch (\PDOException $e) {
            error_log("InventarioModel::actualizarStock ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    final public static function eliminarInventario() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL EliminarInventario(:id_producto, :id_almacen)");
            $stmt->execute([
                ':id_producto' => self::$id_producto,
                ':id_almacen' => self::$id_almacen
            ]);
            return ResponseHTTP::status200("Inventario eliminado correctamente");
        } catch (\PDOException $e) {
            error_log("InventarioModel::eliminarInventario ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }
}
