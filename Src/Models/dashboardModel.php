<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class dashboardModel extends connectionDB
{

    // TOTAL VENTAS

    final public static function totalVentas(){

        try{

            $con = self::getConnection();
            $query = "CALL ConsultarTotalVentas()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            error_log("dashboardModel::totalVentas ".$e);
            return [];
        }

    }

    // TOTAL PRODUCTOS

    final public static function totalProductos(){

        try{
            $con = self::getConnection();
            $query = "CALL ConsultarTotalProductos()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);

        }catch(\PDOException $e){
            error_log("dashboardModel::totalProductos ".$e);
            return [];
        }
    }

    // TOTAL CLIENTES

    final public static function totalClientes(){
        try{
            $con = self::getConnection();
            $query = "CALL ConsultarTotalClientes()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            error_log("dashboardModel::totalClientes ".$e);
            return [];
        }
    }

    // TOTAL COTIZACIONES
    final public static function totalCotizaciones(){
        try{
            $con = self::getConnection();
            $query = "CALL ConsultarTotalCotizaciones()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            error_log("dashboardModel::totalCotizaciones ".$e);
            return [];
        }
    }

    // STOCK TOTAL INVENTARIO

    final public static function stockInventario(){
        try{

            $con = self::getConnection();
            $query = "CALL ConsultarStockInventario()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);

        }catch(\PDOException $e){
            error_log("dashboardModel::stockInventario ".$e);
            return [];
        }

    }
// VENTAS MENSUALES

final public static function ventasMensuales(){
    try{
        $con = self::getConnection();
        $query = "
        CALL ConsultarVentasMensuales()
        ";
        $stmt = $con->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }catch(\PDOException $e){

        error_log(
            "dashboardModel::ventasMensuales ".$e
        );
        return [];
    }
}

    // PRODUCTOS POR CATEGORIA

    final public static function productosCategoria(){

        try{
            $con = self::getConnection();
            $query = "CALL ConsultarProductosCategoria()";
            $stmt = $con->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }catch(\PDOException $e){
            error_log("dashboardModel::productosCategoria ".$e);
            return [];

        }

    }
}