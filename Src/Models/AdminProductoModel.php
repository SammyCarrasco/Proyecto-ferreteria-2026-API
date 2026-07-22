<?php
namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;
use PDO;

class AdminProductoModel extends ConnectionDB {
    private static $id_producto;
    private static $codigo;
    private static $nombre;
    private static $precio_compra;
    private static $precio_venta;
    private static $fotografia;
    private static $id_categoria;
    private static $id_unidad;

    public function __construct(array $data = []) {
        self::$id_producto = $data['id_producto'] ?? null;
        self::$codigo = $data['codigo'] ?? '';
        self::$nombre = $data['nombre'] ?? '';
        self::$precio_compra = $data['precio_compra'] ?? 0;
        self::$precio_venta = $data['precio_venta'] ?? 0;
        self::$fotografia = $data['fotografia'] ?? null;
        self::$id_categoria = $data['id_categoria'] ?? null;
        self::$id_unidad = $data['id_unidad'] ?? null;
    }

    final public static function getId(){ return self::$id_producto; }
    final public static function getCodigo(){ return self::$codigo; }
    final public static function getNombre(){ return self::$nombre; }
    final public static function getPrecioCompra(){ return self::$precio_compra; }
    final public static function getPrecioVenta(){ return self::$precio_venta; }
    final public static function getFotografia(){ return self::$fotografia; }
    final public static function getIdCategoria(){ return self::$id_categoria; }
    final public static function getIdUnidad(){ return self::$id_unidad; }

    // crear producto
    final public static function post() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare(
                "CALL RegistrarProducto(:codigo, :nombre, :precio_compra, :precio_venta, :fotografia, :id_categoria, :id_unidad)"
            );
            $stmt->execute([
                ':codigo' => self::getCodigo(),
                ':nombre' => self::getNombre(),
                ':precio_compra' => self::getPrecioCompra(),
                ':precio_venta' => self::getPrecioVenta(),
                ':fotografia' => self::getFotografia(),
                ':id_categoria' => self::getIdCategoria(),
                ':id_unidad' => self::getIdUnidad(),
            ]);
            return ResponseHTTP::status200('Producto creado exitosamente');
        } catch (\PDOException $e) {
            error_log('AdminProductoModel::post -> ' . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    // consultar todos los productos 
    final public static function get() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ConsultarProductos()");
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ResponseHTTP::status200($res);
        } catch (\PDOException $e) {
            error_log('AdminProductoModel::get -> ' . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    // consultar producto por ID (GET con ?id=)
    final public static function getOne() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ConsultarProductoPorId(:id)");
            $stmt->execute([':id' => self::getId()]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return ResponseHTTP::status200($res ?: null);
        } catch (\PDOException $e) {
            error_log('AdminProductoModel::getOne -> ' . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    // actualizar producto 
    final public static function put() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare(
                "CALL ActualizarProducto(:id, :codigo, :nombre, :precio_compra, :precio_venta, :fotografia, :id_categoria, :id_unidad)"
            );
            $stmt->execute([
                ':id' => self::getId(),
                ':codigo' => self::getCodigo(),
                ':nombre' => self::getNombre(),
                ':precio_compra' => self::getPrecioCompra(),
                ':precio_venta' => self::getPrecioVenta(),
                ':fotografia' => self::getFotografia(),
                ':id_categoria' => self::getIdCategoria(),
                ':id_unidad' => self::getIdUnidad(),
            ]);
            return ResponseHTTP::status200('Producto actualizado exitosamente');
        } catch (\PDOException $e) {
            error_log('AdminProductoModel::put -> ' . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    // eliminar producto 
    final public static function delete() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL EliminarProducto(:id)");
            $stmt->execute([':id' => self::getId()]);
            return ResponseHTTP::status200('Producto eliminado exitosamente');
        } catch (\PDOException $e) {
            error_log('AdminProductoModel::delete -> ' . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }
}
