
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