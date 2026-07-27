use ferreteria;

select * from traducciones;

INSERT INTO traducciones (clave, es, en) VALUES 
('cotizaciones_detalle', 'Cotizaciones — Detalle', 'Quotes — Detail'),
('crear_buscar_modificar_productos_cotizacion', 'Crear, buscar y modificar productos de una cotización', 'Create, search, and modify products in a quote'),
('id_cliente', 'ID Cliente', 'Customer ID'),
('id_empleado', 'ID Empleado', 'Employee ID'),
('nueva_cotizacion', 'Nueva cotización', 'New Quote'),
('cargar_cotizacion_existente_id', 'Cargar cotización existente (ID)', 'Load Existing Quote (ID)'),
('cotizacion', 'Cotización', 'Quote'),
('id_producto', 'ID Producto', 'Product ID'),
('id_almacen', 'ID Almacén', 'Warehouse ID'),
('cantidad', 'Cantidad', 'Quantity'),
('precio_unitario', 'Precio unitario', 'Unit Price'),
('agregar_a_la_cotizacion', 'Agregar a la cotización', 'Add to Quote'),
('producto', 'Producto', 'Product'),
('almacen', 'Almacén', 'Warehouse'),
('precio_unit', 'Precio unit.', 'Unit Price'),
('subtotal', 'Subtotal', 'Subtotal');

INSERT INTO traducciones (clave, es, en) VALUES 
('cotizacion_ya_facturada_no_modificar', 'Esta cotización ya fue facturada — no se puede modificar.', 'This quote has already been invoiced — it cannot be modified.');
INSERT INTO traducciones (clave, es, en) VALUES 
('sin_productos_agregados_todavia', 'Sin productos agregados todavía', 'No products added yet');

INSERT INTO traducciones (clave, es, en) VALUES 
('cargando_catalogo', 'Cargando catálogo...', 'Loading catalog...'),
('catalogo_de_productos', 'Catálogo de productos', 'Product catalog');