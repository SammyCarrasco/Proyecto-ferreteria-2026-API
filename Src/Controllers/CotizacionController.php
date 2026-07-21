<?php
// Src/Controllers/CotizacionController.php

require_once __DIR__ . '/../Models/CotizacionModel.php';

class CotizacionController {
    private CotizacionModel $model;

    public function __construct(PDO $pdo) {
        $this->model = new CotizacionModel($pdo);
    }

    /**
     * Procesa la selección del cliente para el flujo de la cotización.
     */
    public function seleccionarCliente(): void {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id_cliente']) || empty($input['id_cliente'])) {
            $this->responder(400, false, "Se requiere el 'id_cliente' para realizar la selección.");
            return;
        }

        $idCliente = (int)$input['id_cliente'];

        try {
            $existe = $this->model->seleccionarCliente($idCliente);

            if ($existe) {
                $this->responder(200, true, "Cliente seleccionado correctamente.", [
                    "id_cliente" => $idCliente
                ]);
            } else {
                $this->responder(404, false, "Error: El cliente con ID {$idCliente} no existe en la base de datos.");
            }
        } catch (PDOException $e) {
            $this->responder(500, false, "Error al verificar el cliente: " . $e->getMessage());
        }
    }

    /**
     * Procesa la búsqueda de productos.
     */
    public function buscarProductos(): void {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id_empleado']) || empty($input['id_empleado'])) {
            $this->responder(400, false, "Se requiere el 'id_empleado' para continuar.");
            return;
        }

        $criterio = $input['criterio_producto'] ?? $input['criterio'] ?? '';
        if (trim($criterio) === '') {
            $this->responder(400, false, "Se requiere 'criterio_producto' (nombre o ID del producto).");
            return;
        }

        $idEmpleado = (int)$input['id_empleado'];

        try {
            $productos = $this->model->buscarProductos(trim($criterio));

            if (empty($productos)) {
                $this->responder(404, false, "No se encontraron productos coincidentes.");
            } else {
                http_response_code(200);
                echo json_encode([
                    "exito" => true,
                    "empleado_id" => $idEmpleado,
                    "total_encontrados" => count($productos),
                    "data" => $productos
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            $this->responder(500, false, "Error en la búsqueda de productos: " . $e->getMessage());
        }
    }

    /**
     * Procesa la validación de cantidades y cálculo de subtotal por ítem.
     */
    public function validarCantidad(): void {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['id_producto']) || empty($input['id_producto'])) {
            $this->responder(400, false, "Se requiere el 'id_producto'.");
            return;
        }

        if (!isset($input['cantidad']) || (int)$input['cantidad'] <= 0) {
            $this->responder(400, false, "La 'cantidad' debe ser mayor a 0.");
            return;
        }

        $idProducto = (int)$input['id_producto'];
        $cantidad   = (int)$input['cantidad'];
        $idEmpleado = isset($input['id_empleado']) ? (int)$input['id_empleado'] : null;
        $idCliente  = isset($input['id_cliente']) ? (int)$input['id_cliente'] : null;

        try {
            $productoInfo = $this->model->validarCantidadProducto($idProducto, $cantidad);

            if (!$productoInfo) {
                $this->responder(404, false, "El producto con ID {$idProducto} no existe.");
                return;
            }

            if ($cantidad > (int)$productoInfo['stock_disponible']) {
                http_response_code(400);
                echo json_encode([
                    "exito" => false,
                    "mensaje" => "Stock insuficiente. Solicitado: {$cantidad}, Disponible: {$productoInfo['stock_disponible']}.",
                    "stock_disponible" => (int)$productoInfo['stock_disponible']
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->responder(200, true, "Cantidad validada correctamente.", [
                "id_empleado" => $idEmpleado,
                "id_cliente" => $idCliente,
                "id_producto" => (int)$productoInfo['id_producto'],
                "nombre" => $productoInfo['nombre'],
                "cantidad" => $cantidad,
                "precio_unitario" => (float)$productoInfo['precio_unitario'],
                "subtotal" => (float)$productoInfo['subtotal_linea'],
                "stock_disponible" => (int)$productoInfo['stock_disponible'],
                "id_almacen" => (int)$productoInfo['id_almacen']
            ]);
        } catch (PDOException $e) {
            $this->responder(500, false, "Error al validar la cantidad: " . $e->getMessage());
        }
    }

    /**
     * Calcula subtotal y total general de un conjunto de productos.
     */
    public function calcularTotal(): void {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['productos']) || !is_array($input['productos']) || empty($input['productos'])) {
            $this->responder(400, false, "Se requiere la lista de 'productos' con al menos un ítem.");
            return;
        }

        $idEmpleado = isset($input['id_empleado']) ? (int)$input['id_empleado'] : null;
        $idCliente  = isset($input['id_cliente']) ? (int)$input['id_cliente'] : null;

        $detalleProcesado = [];
        $subtotalGeneral = 0.00;

        try {
            foreach ($input['productos'] as $index => $item) {
                $idProducto = (int)($item['id_producto'] ?? 0);
                $cantidad   = (int)($item['cantidad'] ?? 0);

                if ($idProducto <= 0 || $cantidad <= 0) {
                    $this->responder(400, false, "El ítem en el índice {$index} no tiene un 'id_producto' o 'cantidad' válida.");
                    return;
                }

                $productoInfo = $this->model->obtenerPrecioYStock($idProducto);

                if (!$productoInfo) {
                    $this->responder(404, false, "El producto con ID {$idProducto} no existe.");
                    return;
                }

                if ($cantidad > (int)$productoInfo['stock_disponible']) {
                    $this->responder(400, false, "Stock insuficiente para '{$productoInfo['nombre']}'. Solicitado: {$cantidad}, Disponible: {$productoInfo['stock_disponible']}.");
                    return;
                }

                $precioUnitario = (float)$productoInfo['precio_unitario'];
                $subtotalLinea  = $precioUnitario * $cantidad;
                $subtotalGeneral += $subtotalLinea;

                $detalleProcesado[] = [
                    "id_producto" => $idProducto,
                    "nombre" => $productoInfo['nombre'],
                    "cantidad" => $cantidad,
                    "precio_unitario" => $precioUnitario,
                    "subtotal_linea" => round($subtotalLinea, 2),
                    "id_almacen" => (int)$productoInfo['id_almacen']
                ];
            }

            http_response_code(200);
            echo json_encode([
                "exito" => true,
                "mensaje" => "Cálculo del total realizado exitosamente.",
                "resumen" => [
                    "id_empleado" => $idEmpleado,
                    "id_cliente" => $idCliente,
                    "subtotal" => round($subtotalGeneral, 2),
                    "total" => round($subtotalGeneral, 2),
                    "total_items" => count($detalleProcesado)
                ],
                "detalle" => $detalleProcesado
            ], JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            $this->responder(500, false, "Error al calcular el total: " . $e->getMessage());
        }
    }

    /**
     * Procesa la reserva de inventario para una lista de productos.
     */
    public function reservarInventario(): void {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['productos']) || !is_array($input['productos']) || empty($input['productos'])) {
            $this->responder(400, false, "Se requiere la lista de 'productos' a reservar.");
            return;
        }

        $idEmpleado = isset($input['id_empleado']) ? (int)$input['id_empleado'] : null;
        $idCliente  = isset($input['id_cliente']) ? (int)$input['id_cliente'] : null;

        $reservasRealizadas = [];

        try {
            $this->model->iniciarTransaccion();

            foreach ($input['productos'] as $index => $item) {
                $idProducto = (int)($item['id_producto'] ?? 0);
                $cantidad   = (int)($item['cantidad'] ?? 0);

                if ($idProducto <= 0 || $cantidad <= 0) {
                    $this->model->cancelarTransaccion();
                    $this->responder(400, false, "Datos inválidos en el ítem del índice {$index}.");
                    return;
                }

                $inventarioActualizado = $this->model->reservarProducto($idProducto, $cantidad);

                $reservasRealizadas[] = [
                    "id_producto" => $idProducto,
                    "cantidad_reservada" => $cantidad,
                    "nuevo_stock_disponible" => (int)$inventarioActualizado['stock_disponible'],
                    "nuevo_stock_reservado" => (int)$inventarioActualizado['stock_reservado'],
                    "id_almacen" => (int)$inventarioActualizado['id_almacen']
                ];
            }

            $this->model->confirmarTransaccion();

            $this->responder(200, true, "Reserva de inventario realizada exitosamente.", [
                "id_empleado" => $idEmpleado,
                "id_cliente" => $idCliente,
                "detalles_reserva" => $reservasRealizadas
            ]);
        } catch (PDOException $e) {
            $this->model->cancelarTransaccion();
            $this->responder(400, false, "Error al reservar inventario: " . $e->getMessage());
        }
    }

    /**
     * Helper privado para formatear respuestas JSON estándar.
     */
    private function responder(int $code, bool $exito, string $mensaje, array $data = null): void {
        http_response_code($code);
        $response = ["exito" => $exito, "mensaje" => $mensaje];
        if ($data !== null) {
            $response["data"] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}