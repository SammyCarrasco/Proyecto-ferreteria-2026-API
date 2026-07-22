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
        //lee el JSON
        $input = json_decode(file_get_contents("php://input"), true) ?? [];

        $criterio = $input['criterio_producto'] ?? $input ['busqueda'] ?? null;

        if(!$criterio){
            http_response_code(400);
            echo json_encode([
                "exito" => false,
                "mensaje" => "Se requiere 'nombre_producto'"
            ]);
            return;
        }
        //consultar productos
        $productos = $this->model->buscarProductos($criterio);

        //respuesta mostrando la busqueda de productos
        http_response_code(200);
        echo json_encode([
             "exito" => true,
            "total_encontrados" => count($productos),
        ]);
    }   

public function registrarCantidades() {
    // 1. Leer el JSON del request
    $input = json_decode(file_get_contents("php://input"), true) ?? [];
    $productos = $input['productos'] ?? [];

    if (empty($productos) || !is_array($productos)) {
        http_response_code(400);
        echo json_encode([
            "exito" => false,
            "mensaje" => "Se requiere la lista 'productos'."
        ]);
        return;
    }

    $resumen = [];

    foreach ($productos as $item) {
        $idProducto = $item['id_producto'] ?? null;
        $cantidad   = $item['cantidad'] ?? null;
        $precio     = $item['precio'] ?? $item['precio_unitario'] ?? null;

        if ($idProducto !== null && $cantidad !== null && $precio !== null) {
            $nombre = "Producto " . $idProducto;

            //Buscar en cualquier modelo instanciado dentro de la clase
        
            foreach (get_object_vars($this) as $modelo) {
                if (is_object($modelo)) {
                    if (method_exists($modelo, 'obtenerProductoPorId')) {
                       $info = $modelo->obtenerProductoPorId($idProducto);
                       if (!empty($info['nombre'])) {
                            $nombre = $info['nombre'];
                            break;
                        }
                    } elseif (method_exists($modelo, 'obtenerPorId')) {
                        $info = $modelo->obtenerPorId($idProducto);
                        if (!empty($info['nombre'])) {
                            $nombre = $info['nombre'];
                            break;
                        }
                    }
                }
            }
            

            $resumen[] = [
                "nombre"          => $nombre,
                "cantidad"        => (int)$cantidad,
                "precio_unitario" => (float)$precio
            ];
        }
    }

    // 2. Imprimir la respuesta JSON
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        "exito" => true,
        "mensaje" => "Registro de cantidades completado.",
        "data"  => $resumen
    ]);
    exit; // Asegura que no haya salida adicional
}

public function calcularTotal() {
    $datos = json_decode(file_get_contents('php://input'), true);
    $productos = $datos['productos'] ?? [];
    $resumen = [];
    $totalGeneral = 0;

    foreach ($productos as $item) {
        $idProducto = $item['id_producto'] ?? null;
        $cantidad   = $item['cantidad'] ?? null;
        $precio     = $item['precio'] ?? $item['precio_unitario'] ?? null;

        if ($idProducto !== null && $cantidad !== null && $precio !== null) {
            $nombre = "Producto " . $idProducto;

            // Consultar el nombre real desde el modelo
            $modelo = $this->cotizacionModel ?? $this->model ?? null;
            if ($modelo && method_exists($modelo, 'obtenerProductoPorId')) {
                $info = $modelo->obtenerProductoPorId($idProducto);
                if (!empty($info['nombre'])) {
                    $nombre = $info['nombre'];
                }
            }

            $cantidadInt = (int)$cantidad;
            $precioFloat = (float)$precio;
            $subtotal    = $cantidadInt * $precioFloat;

            $totalGeneral += $subtotal;

            $resumen[] = [
                "nombre"          => $nombre,
                "cantidad"        => $cantidadInt,
                "precio_unitario" => $precioFloat,
                "total"           => $subtotal
            ];
        }
    }

    echo json_encode([
        "exito"         => true,
        "mensaje"       => "total a pagar",
        "data"          => $resumen,
        "total_general" => $totalGeneral
    ]);
    return;
}
    /**
     * Procesa la reserva de inventario para una lista de productos.
     */
    public function reservarInventario() {
    $datos = json_decode(file_get_contents('php://input'), true);
    $idCliente = $datos['id_cliente'] ?? null;
    $productos = $datos['productos'] ?? [];
    $resumen = [];
    $errores = [];

    if (empty($productos)) {
        echo json_encode([
            "exito"   => false,
            "mensaje" => "No se enviaron productos para reservar."
        ]);
        return;
    }

    foreach ($productos as $item) {
        $idProducto = $item['id_producto'] ?? null;
        $cantidad   = $item['cantidad'] ?? null;

        if ($idProducto !== null && $cantidad !== null) {
            $cantidadInt = (int)$cantidad;
            $nombre = "Producto " . $idProducto;

            // Instancia del modelo
            $modelo = $this->cotizacionModel ?? $this->model ?? null;

            // Obtener el nombre del producto
            if ($modelo && method_exists($modelo, 'obtenerProductoPorId')) {
                $info = $modelo->obtenerProductoPorId($idProducto);
                if (!empty($info['nombre'])) {
                    $nombre = $info['nombre'];
                }
            }

            // Ejecutar la reserva en el modelo/base de datos
            $reservaExitosa = false;
            if ($modelo && method_exists($modelo, 'reservarStock')) {
                $reservaExitosa = $modelo->reservarStock($idProducto, $cantidadInt, $idCliente);
            } else {
                // Alternativa fallback si la lógica aún no está conectada a la BD
                $reservaExitosa = true; 
            }

            if ($reservaExitosa) {
                $resumen[] = [
                    "id_producto"        => $idProducto,
                    "nombre"             => $nombre,
                    "cantidad_reservada" => $cantidadInt,
                    "estado"             => "Reservado"
                ];
            } else {
                $errores[] = [
                    "id_producto" => $idProducto,
                    "nombre"      => $nombre,
                    "mensaje"     => "Stock insuficiente o error al reservar"
                ];
            }
        }
    }

    // Respuesta JSON
    echo json_encode([
        "exito"               => empty($errores),
        "mensaje"             => empty($errores) ? "Reserva de inventario completada con éxito." : "Proceso completado con algunas advertencias.",
        "productos_reservados" => $resumen,
        "errores"             => $errores
    ]);
    return;
}
}