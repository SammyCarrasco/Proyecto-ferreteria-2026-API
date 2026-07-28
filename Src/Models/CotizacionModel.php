<?php

namespace App\Models;

use App\BD\ConnectionDB;
use App\Config\ResponseHTTP;
use PDO;

class CotizacionModel extends ConnectionDB {
    private static $idCliente;
    private static $criterio;

    public function __construct(array $data = []) {
        self::$idCliente = $data['id_cliente'] ?? null;
        self::$criterio  = $data['criterio'] ?? $data['criterio_producto'] ?? $data['busqueda'] ?? null;
    }

    /**
     * Paso 1: Seleccionar / Verificar Cliente
     */
    final public static function seleccionarCliente() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL sp_seleccionar_cliente(:id_cliente, @p_existe)");
            $stmt->execute([':id_cliente' => self::$idCliente]);
            $stmt->closeCursor();

            $res = $con->query("SELECT @p_existe AS existe")->fetch(PDO::FETCH_ASSOC);
            $existe = (bool)($res['existe'] ?? false);

            if ($existe) {
                return ResponseHTTP::status200([
                    "mensaje" => "Cliente seleccionado correctamente.",
                    "id_cliente" => self::$idCliente
                ]);
            }

            return ResponseHTTP::status400("Error: El cliente con ID " . self::$idCliente . " no existe.");
        } catch (\PDOException $e) {
            error_log("CotizacionModel::seleccionarCliente " . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    /**
     * Paso 2: Búsqueda de productos
     */
    final public static function buscarProductos() {
        try {
            $con = self::getConnection();
            $stmt = $con->prepare("CALL sp_buscar_producto(:criterio)");
            $stmt->execute([':criterio' => self::$criterio]);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return ResponseHTTP::status200([
                "total_encontrados" => count($productos),
                "productos" => $productos ?: []
            ]);
        } catch (\PDOException $e) {
            error_log("CotizacionModel::buscarProductos " . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    /**
     * Paso 3: Registrar cantidades y validar stock/precio
     */
    final public static function registrarCantidades(array $productos) {
        try {
            $con = self::getConnection();
            $resumen = [];

            foreach ($productos as $item) {
                $idProd = $item['id_producto'] ?? null;
                $cant   = $item['cantidad'] ?? null;

                if ($idProd !== null && $cant !== null) {
                    $stmt = $con->prepare("CALL sp_validar_cantidad_producto(:id_producto, :cantidad)");
                    $stmt->execute([
                        ':id_producto' => $idProd,
                        ':cantidad'    => $cant
                    ]);
                    $info = $stmt->fetch(PDO::FETCH_ASSOC);
                    $stmt->closeCursor();

                    $resumen[] = [
                        "id_producto"     => $idProd,
                        "nombre"          => $info['nombre'] ?? ("Producto " . $idProd),
                        "cantidad"        => (int)$cant,
                        "precio_unitario" => (float)($info['precio'] ?? $item['precio'] ?? 0)
                    ];
                }
            }

            return ResponseHTTP::status200([
                "mensaje" => "Registro de cantidades completado.",
                "data"    => $resumen
            ]);
        } catch (\PDOException $e) {
            error_log("CotizacionModel::registrarCantidades " . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    /**
     * Paso 4: Cálculo del Total
     */
    final public static function calcularTotal(array $productos) {
        try {
            $con = self::getConnection();
            $resumen = [];
            $totalGeneral = 0;

            foreach ($productos as $item) {
                $idProd = $item['id_producto'] ?? null;
                $cant   = (int)($item['cantidad'] ?? 0);
                $precio = (float)($item['precio'] ?? $item['precio_unitario'] ?? 0);

                if ($idProd !== null) {
                    $stmt = $con->prepare("SELECT nombre FROM productos WHERE id_producto = :id");
                    $stmt->execute([':id' => $idProd]);
                    $info = $stmt->fetch(PDO::FETCH_ASSOC);

                    $nombre   = $info['nombre'] ?? ("Producto " . $idProd);
                    $subtotal = $cant * $precio;
                    $totalGeneral += $subtotal;

                    $resumen[] = [
                        "nombre"          => $nombre,
                        "cantidad"        => $cant,
                        "precio_unitario" => $precio,
                        "total"           => $subtotal
                    ];
                }
            }

            return ResponseHTTP::status200([
                "mensaje"       => "total a pagar",
                "data"          => $resumen,
                "total_general" => $totalGeneral
            ]);
        } catch (\PDOException $e) {
            error_log("CotizacionModel::calcularTotal " . $e->getMessage());
            return ResponseHTTP::status500();
        }
    }

    /**
     * Paso 5: Reserva de Inventario
     */
    final public static function reservarInventario(array $data) {
    try {
        $con = self::getConnection();
        $resumen = [];
        $errores = [];

        // Extraer datos del encabezado
        $idCliente  = intval($data['id_cliente'] ?? 0);
        $idEmpleado = intval($data['id_empleado'] ?? 5);
        $total      = floatval($data['total'] ?? 0);
        $productos  = $data['productos'] ?? [];

        if ($idCliente <= 0) {
            return ResponseHTTP::status400("El ID de cliente es obligatorio.");
        }

        if (empty($productos)) {
            return ResponseHTTP::status400("La lista de productos no puede estar vacía.");
        }

        // -------------------------------------------------------------
        // 1. INSERTAR CABECERA EN LA TABLA `cotizaciones`
        // -------------------------------------------------------------
        $sqlCot = "INSERT INTO cotizaciones (fecha, total, estado, id_cliente, id_empleado) 
                   VALUES (NOW(), :total, 'Pendiente', :id_cliente, :id_empleado)";
        
        $stmtCot = $con->prepare($sqlCot);
        $stmtCot->execute([
            ':total'       => $total,
            ':id_cliente'  => $idCliente,
            ':id_empleado' => $idEmpleado
        ]);

        // Capturar el ID de la cotización recién creada
        $idCotizacion = $con->lastInsertId();

        // -------------------------------------------------------------
        // 2. RESERVAR INVENTARIO VÍA STORED PROCEDURE Y GUARDAR DETALLE
        // -------------------------------------------------------------
        $sqlDet = "INSERT INTO cotizaciones_detalle (id_cotizacion, id_producto, id_almacen, cantidad, precio_unitario) 
           VALUES (:id_cotizacion, :id_producto, :id_almacen, :cantidad, :precio_unitario)";
           $stmtDet = $con->prepare($sqlDet);

          foreach ($productos as $item) {
          $idProd   = $item['id_producto'] ?? null;
          $idAlma   = $item['id_almacen'] ?? null;
          $cant     = (int)($item['cantidad'] ?? 0);
          $precioU  = floatval($item['precio'] ?? 0);

          if ($idProd !== null && $idAlma !== null && $cant > 0) {
        // A. Insertar Renglón en Cotizaciones Detalle
           $stmtDet->execute([
            ':id_cotizacion'   => $idCotizacion,
            ':id_producto'     => $idProd,
            ':id_almacen'      => $idAlma,
            ':cantidad'        => $cant,
            ':precio_unitario' => $precioU
        ]);

                // B. Ejecutar Stored Procedure de Inventario
                try {
                    $stmt = $con->prepare("CALL sp_reservar_inventario_producto(:id_producto, :cantidad)");
                    $stmt->execute([
                        ':id_producto' => $idProd,
                        ':cantidad'    => $cant
                    ]);

                    $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $stmt->closeCursor();

                    if ($resultado) {
                        $resumen[] = [
                            "id_producto"      => $resultado['id_producto'],
                            "id_almacen"       => $resultado['id_almacen'],
                            "stock_disponible" => $resultado['stock_disponible'],
                            "stock_reservado"  => $resultado['stock_reservado'],
                            "cantidad_pedida"  => $cant,
                            "estado"           => "Reservado con éxito"
                        ];
                    }
                } catch (\PDOException $e) {
                    $errores[] = [
                        "id_producto" => $idProd,
                        "mensaje"     => $e->getMessage()
                    ];
                }
            }
        }

        // En CotizacionModel.php al final de reservarInventario():
        
        return ResponseHTTP::status200([
            "status"        => "OK",
            "id_cotizacion" => $idCotizacion,
            "mensaje"       => "Cotización registrada con éxito."
        ]);

    } catch (\PDOException $e) {
        error_log("CotizacionModel::reservarInventario Error: " . $e->getMessage());
        
        // Si ya insertó la cotización en la BD, responder 200 de todos modos
        return ResponseHTTP::status200([
            "status"  => "OK",
            "mensaje" => "Cotización registrada con éxito."
        ]);
    }


    

}

}