<?php

namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;
use PDO;

class AlmacenModel extends ConnectionDB {
    private static $id_almacen;
    private static $nombre;
    private static $ubicacion;

    public function __construct(array $data = []) {
        self::$id_almacen = $data['id_almacen'] ?? null;
        self::$nombre     = $data['nombre'] ?? '';
        self::$ubicacion  = $data['ubicacion'] ?? '';
    }

    final public static function registrarAlmacen() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL RegistrarAlmacen(:nombre, :ubicacion)");
            $stmt->execute([
                ':nombre' => self::$nombre,
                ':ubicacion' => self::$ubicacion
            ]);
            return ResponseHTTP::status200("Almacén registrado exitosamente");
        } catch (\PDOException $e) {
            error_log("AlmacenModel::registrarAlmacen ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    final public static function getAll() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ConsultarAlmacenes()");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("AlmacenModel::getAll ".$e->getMessage());
            return [];
        }
    }

    final public static function actualizarAlmacen($id) {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ActualizarAlmacen(:id, :nombre, :ubicacion)");
            $stmt->execute([
                ':id' => $id,
                ':nombre' => self::$nombre,
                ':ubicacion' => self::$ubicacion
            ]);
            return ResponseHTTP::status200("Almacén actualizado correctamente");
        } catch (\PDOException $e) {
            error_log("AlmacenModel::actualizarAlmacen ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    final public static function eliminarAlmacen($id) {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL EliminarAlmacen(:id)");
            $stmt->execute([':id' => $id]);
            return ResponseHTTP::status200("Almacén eliminado correctamente");
        } catch (\PDOException $e) {
            error_log("AlmacenModel::eliminarAlmacen ".$e->getMessage());
            return ResponseHTTP::status500();
        }
    }
}
