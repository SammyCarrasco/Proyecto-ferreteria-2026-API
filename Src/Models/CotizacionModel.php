<?php
// Src/Models/CotizacionModel.php

class CotizacionModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Paso 1: Verifica si el cliente existe en la base de datos.
     */
    public function seleccionarCliente(int $idCliente): bool {
        $sql = "CALL sp_seleccionar_cliente(:id_cliente, @p_existe)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $idCliente, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();

        $res = $this->pdo->query("SELECT @p_existe AS existe")->fetch(PDO::FETCH_ASSOC);

        return (bool)($res['existe'] ?? false);
    }

    /**
     * Paso 2: Búsqueda de productos por ID o coincidencia en el nombre.
     */
    public function buscarProductos(string $criterio): array {
        $sql = "CALL sp_buscar_producto(:criterio)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':criterio', $criterio, PDO::PARAM_STR);
        $stmt->execute();

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $productos ?: [];
    }

    /**
     * Paso 3: Valida stock y obtiene precios/subtotales para un producto y cantidad.
     */
    public function validarCantidadProducto(int $idProducto, int $cantidad) {
        $sql = "CALL sp_validar_cantidad_producto(:id_producto, :cantidad)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $resultado;
    }

    // ... otros métodos de la clase ...

    public function obtenerProductoPorId($idProducto) {
        $sql = "SELECT nombre FROM productos WHERE id_producto = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $idProducto]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

 

    /**
     * Obtiene precio unitario y stock disponible de un producto.
     */
    public function obtenerPrecioYStock(int $idProducto) {
        $sql = "CALL sp_obtener_precio_y_stock(:id_producto)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $resultado;
    }

    /**
     * Reserva el inventario de un producto en la base de datos.
     */
    public function reservarProducto(int $idProducto, int $cantidad) {
        $sql = "CALL sp_reservar_inventario_producto(:id_producto, :cantidad)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_producto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $resultado;
    }

    /* --- Gestión de Transacciones --- */

    public function iniciarTransaccion(): void {
        $this->pdo->beginTransaction();
    }

    public function confirmarTransaccion(): void {
        $this->pdo->commit();
    }

    public function cancelarTransaccion(): void {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}