<?php

namespace App\Models;

use App\BD\ConnectionDB;
use PDO;

class ReportesModel extends ConnectionDB {

    // 1. Cotizaciones generales
    public static function obtenerCotizacionesGenerales($fechaInicio, $fechaFin) {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_cotizaciones_generales(:fecha_inicio, :fecha_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Cotizaciones por cliente
    public static function obtenerCotizacionesPorCliente($idCliente, $fechaInicio, $fechaFin) {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_cotizaciones_cliente(:id_cliente, :fecha_inicio, :fecha_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':id_cliente', $idCliente);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Facturas
    public static function obtenerFacturas($fechaInicio, $fechaFin) {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_facturas(:fecha_inicio, :fecha_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. ISV Generado
    public static function obtenerISV($fechaInicio, $fechaFin) {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_isv(:fecha_inicio, :fecha_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Ganancias
    public static function obtenerGanancias($fechaInicio, $fechaFin) {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_ganancias(:fecha_inicio, :fecha_fin)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':fecha_inicio', $fechaInicio);
        $stmt->bindValue(':fecha_fin', $fechaFin);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Inversión
    public static function obtenerInversion() {
        $con = parent::getConnection();
        $sql = "CALL sp_reporte_inversion()";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}