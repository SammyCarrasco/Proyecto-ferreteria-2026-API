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


/*Form_adminproducts*/
INSERT INTO traducciones (clave, es, en) VALUES 
('codigo', 'Código', 'Code'),
('nombre', 'Nombre', 'Name'),
('categoria', 'Categoría', 'Category'),
('unidad', 'Unidad', 'Unit'),
('precio_compra', 'Precio Compra', 'Purchase Price'),
('precio_venta', 'Precio Venta', 'Selling Price'),
('nuevo_producto', 'Nuevo Producto', 'New Product'),
('acciones', 'Acciones', 'Actions'),
('fotografia_url_opcional', 'Fotografía (URL, opcional)', 'Photograph (URL, optional)'),
('unidad_de_medida', 'Unidad de Medida', 'Unit of Measure'),
('metro', 'Metro', 'Meter'),
('libra', 'Libra', 'Pound'),
('caja', 'Caja', 'Box'),
('guardar', 'Guardar', 'Save'),
('cancelar', 'Cancelar', 'Cancel'),
('consultar_por_id', 'Consultar por ID', 'Search by ID'),
('gestion_catalogo_productos', 'Gestión del catálogo de productos de la ferretería', 'Hardware store product catalog management'),
('administracion_de_productos', 'Administración de Productos', 'Product Management');

/*cotizaciones*/
INSERT IGNORE INTO traducciones (clave, es, en) VALUES 
('modulo_cotizaciones', 'Módulo de Cotizaciones', 'Quotes Module'),
('proceso_registro_validacion', 'Proceso de registro y validación por etapas', 'Multi-stage registration and validation process'),
('seleccion_del_cliente', 'Selección del Cliente', 'Customer Selection'),
('id_del_cliente', 'ID del Cliente', 'Customer ID'),
('validar_cliente', 'Validar Cliente', 'Validate Customer'),
('ingrese_id_continuar', 'Ingrese el ID para continuar', 'Enter ID to continue'),
('busqueda_de_productos', 'Búsqueda de Productos', 'Product Search'),
('buscar', 'Buscar', 'Search'),
('id', 'ID', 'ID'),
('precio', 'Precio', 'Price'),
('accion', 'Acción', 'Action'),
('realice_busqueda_mostrar_productos', 'Realice una búsqueda para mostrar productos', 'Perform a search to display products'),
('cantidades_y_totales', 'Cantidades y Totales', 'Quantities and Totals'),
('precio_u', 'Precio U.', 'Unit Price'),
('subtotal', 'Subtotal', 'Subtotal'),
('no_hay_productos_agregados', 'No hay productos agregados a la cotización', 'No products added to the quote'),
('calcular_total', 'Calcular Total', 'Calculate Total'),
('subtotal_neto', 'Subtotal Neto:', 'Net Subtotal:'),
('isv_15', 'ISV (15%):', 'Sales Tax (15%):'),
('total_general', 'Total General:', 'Grand Total:'),
('registro_de_cotizacion', 'Registro de Cotización', 'Quote Registration'),
('fecha', 'fecha', 'date'),
('total', 'total', 'total'),
('estado', 'estado', 'status'),
('id_cliente_col', 'id_cliente', 'customer_id'),
('id_empleado_col', 'id_empleado', 'employee_id'),
('calcule_totales_para_generar_registro', 'Calcule los totales en el Paso 3 y 4 para generar el registro de la cotización', 'Calculate totals in Steps 3 and 4 to generate the quote record'),
('confirmar_y_reservar', 'Confirmar y Reservar', 'Confirm and Reserve');

/*clientes*/
INSERT IGNORE INTO traducciones (clave, es, en) VALUES 
('gestion_de_clientes', 'Gestión de Clientes', 'Customer Management'),
('administracion_y_registro_clientes', 'Administración y registro de clientes', 'Customer administration and registration'),
('nuevo_cliente', 'Nuevo Cliente', 'New Customer'),
('id', 'ID', 'ID'),
('rtn', 'RTN', 'Tax ID / RTN'),
('nombre', 'Nombre', 'Name'),
('telefono', 'Teléfono', 'Phone'),
('fecha_registro', 'Fecha Registro', 'Registration Date'),
('acciones', 'Acciones', 'Actions'),
('cancelar', 'Cancelar', 'Cancel'),
('guardar', 'Guardar', 'Save');


/*categorias*/
INSERT IGNORE INTO traducciones (clave, es, en) VALUES 
('gestion_de_categorias', 'Gestión de Categorías', 'Category Management'),
('administracion_registro_categorias', 'Administración y registro de categorías de productos', 'Product category administration and registration'),
('nueva_categoria', 'Nueva Categoría', 'New Category'),
('id', 'ID', 'ID'),
('nombre', 'Nombre', 'Name'),
('descripcion', 'Descripción', 'Description'),
('acciones', 'Acciones', 'Actions'),
('cancelar', 'Cancelar', 'Cancel'),
('guardar', 'Guardar', 'Save');