
DELIMITER $$

CREATE PROCEDURE ConsultarTotalVentas()
BEGIN

    SELECT 
        IFNULL(SUM(total),0) AS total_ventas
    FROM ventas;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE ConsultarTotalProductos()
BEGIN

    SELECT 
        COUNT(*) AS total_productos
    FROM productos;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE ConsultarTotalClientes()
BEGIN

    SELECT 
        COUNT(*) AS total_clientes
    FROM clientes;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE ConsultarTotalCotizaciones()
BEGIN

    SELECT 
        COUNT(*) AS total_cotizaciones
    FROM cotizaciones;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE ConsultarStockInventario()
BEGIN

    SELECT 
        IFNULL(SUM(stock_disponible),0) AS stock_total
    FROM inventario;

END$$

DELIMITER ;



DELIMITER $$

CREATE PROCEDURE ConsultarVentasMensuales()
BEGIN

    SELECT

        MONTH(fecha) AS mes_numero,

        MONTHNAME(fecha) AS mes,

        SUM(total) AS total

    FROM ventas

    GROUP BY 
        MONTH(fecha),
        MONTHNAME(fecha)

    ORDER BY 
        mes_numero;

END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE ConsultarProductosCategoria()
BEGIN

    SELECT

        c.nombre AS categoria,

        COUNT(p.id_producto) AS cantidad_productos

    FROM categorias c

    INNER JOIN productos p

    ON c.id_categoria = p.id_categoria

    GROUP BY 
        c.id_categoria,
        c.nombre;

END$$

DELIMITER ;


/*******ANA******/

DELIMITER $$
CREATE PROCEDURE sp_procesar_venta_factura(
    IN p_id_cotizacion INT,
    IN p_id_empleado INT
)
BEGIN
    DECLARE v_estado VARCHAR(20);
    DECLARE v_subtotal DECIMAL(10,2);
    DECLARE v_isv DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_id_venta INT;
    DECLARE v_fin INT DEFAULT 0;
    DECLARE v_id_producto INT;
    DECLARE v_id_almacen INT;
    DECLARE v_cantidad INT;

    DECLARE cur_detalle CURSOR FOR
        SELECT id_producto, id_almacen, cantidad
        FROM cotizaciones_detalle
        WHERE id_cotizacion = p_id_cotizacion;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_fin = 1;

    SELECT estado, total INTO v_estado, v_subtotal
    FROM cotizaciones
    WHERE id_cotizacion = p_id_cotizacion;

    IF v_estado IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La cotización no existe.';
    ELSEIF v_estado <> 'Pendiente' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Esta cotización ya fue facturada o está cancelada.';
    ELSE
        SET v_isv = ROUND(v_subtotal * 0.15, 2);
        SET v_total = v_subtotal + v_isv;

        INSERT INTO ventas(nro_factura, id_cotizacion, id_empleado, subtotal, isv, total)
        VALUES ('TEMP', p_id_cotizacion, p_id_empleado, v_subtotal, v_isv, v_total);

        SET v_id_venta = LAST_INSERT_ID();
        UPDATE ventas SET nro_factura = CONCAT('FAC-', LPAD(v_id_venta, 6, '0')) WHERE id_venta = v_id_venta;

        OPEN cur_detalle;
        leer_detalle: LOOP
            FETCH cur_detalle INTO v_id_producto, v_id_almacen, v_cantidad;
            IF v_fin = 1 THEN
                LEAVE leer_detalle;
            END IF;
            UPDATE inventario
            SET stock_reservado = stock_reservado - v_cantidad
            WHERE id_producto = v_id_producto AND id_almacen = v_id_almacen;
        END LOOP;
        CLOSE cur_detalle;

        UPDATE cotizaciones SET estado = 'Facturada' WHERE id_cotizacion = p_id_cotizacion;
    END IF;
END$$
DELIMITER ;