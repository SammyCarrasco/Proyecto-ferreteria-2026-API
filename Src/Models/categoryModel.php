<?php

namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;
use PDO;

class CategoryModel extends ConnectionDB {
    private static $id_categoria;
    private static $nombre;
    private static $descripcion;

    public function __construct(array $data = []) {
        self::$id_categoria = $data['id_categoria'] ?? null;
        self::$nombre       = $data['nombre'] ?? '';
        self::$descripcion  = $data['descripcion'] ?? '';
    }

    // Getters
    final public static function getId()          { return self::$id_categoria; }
    final public static function getNombre()      { return self::$nombre; }
    final public static function getDescripcion() { return self::$descripcion; }

    // CREAR CATEGORÍA (POST)
    final public static function post() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL RegistrarCategoria(:nombre, :descripcion)");
            $stmt->execute([
                ':nombre'      => self::getNombre(),
                ':descripcion' => self::getDescripcion()
            ]);

            return ResponseHTTP::status200('Categoría creada exitosamente');
        } catch (\PDOException $e) {
            error_log('CategoryModel::post -> ' . $e->getMessage());
            return ResponseHTTP::status500('Error en la base de datos: ' . $e->getMessage());
        }
    }

    // CONSULTAR CATEGORÍAS (GET)
    final public static function get() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ConsultarCategorias()");
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ResponseHTTP::status200($res);
        } catch (\PDOException $e) {
            error_log('CategoryModel::get -> ' . $e->getMessage());
            return ResponseHTTP::status500('Error en la base de datos: ' . $e->getMessage());
        }
    }

    // ACTUALIZAR CATEGORÍA (PUT)
    final public static function put() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL ActualizarCategoria(:id, :nombre, :descripcion)");
            $stmt->execute([
                ':id'          => self::getId(),
                ':nombre'      => self::getNombre(),
                ':descripcion' => self::getDescripcion()
            ]);

            return ResponseHTTP::status200('Categoría actualizada exitosamente');
        } catch (\PDOException $e) {
            error_log('CategoryModel::put -> ' . $e->getMessage());
            return ResponseHTTP::status500('Error en la base de datos: ' . $e->getMessage());
        }
    }

    // ELIMINAR CATEGORÍA (DELETE)
    final public static function delete() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL EliminarCategoria(:id)");
            $stmt->execute([':id' => self::getId()]);

            return ResponseHTTP::status200('Categoría eliminada exitosamente');
        } catch (\PDOException $e) {
            error_log('CategoryModel::delete -> ' . $e->getMessage());
            return ResponseHTTP::status500('Error en la base de datos: ' . $e->getMessage());
        }
    }
}