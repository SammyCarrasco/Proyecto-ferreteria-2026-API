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
INSERT IGNORE INTO traducciones (clave, es, en) VALUES
('cat_titulo', 'Gestión de Categorías', 'Category Management'),
('cat_subtitulo', 'Administración y registro de categorías de productos', 'Product category management and registration'),
('cat_btn_nueva', 'Nueva Categoría', 'New Category'),
('tbl_id', 'ID', 'ID'),
('tbl_nombre', 'Nombre', 'Name'),
('tbl_descripcion', 'Descripción', 'Description'),
('tbl_acciones', 'Acciones', 'Actions'),
('modal_cat_titulo_nueva', 'Nueva Categoría', 'New Category'),
('modal_cat_titulo_editar', 'Editar Categoría', 'Edit Category'),
('lbl_nombre', 'Nombre', 'Name'),
('lbl_descripcion', 'Descripción', 'Description'),
('ph_nombre', 'Ej. Herramientas', 'E.g. Tools'),
('ph_descripcion', 'Descripción...', 'Description...'),
('btn_cancelar', 'Cancelar', 'Cancel'),
('btn_guardar', 'Guardar', 'Save'),
('btn_guardando', 'Guardando...', 'Saving...'),
('btn_editar', 'Editar', 'Edit'),
('btn_eliminar', 'Eliminar', 'Delete'),
('msg_cargando', 'Cargando categorías...', 'Loading categories...'),
('msg_error_consultar', 'Error al consultar datos.', 'Error fetching data.'),
('msg_sin_categorias', 'No hay categorías registradas.', 'No categories registered.');

/*reportes*/
INSERT IGNORE INTO traducciones (clave, es, en) VALUES
('rep_titulo_modulo', 'Módulo de Reportes', 'Reports Module'),
('rep_subtitulo_modulo', 'Consulta de facturas, cotizaciones, ISV, ganancias e inversión', 'Invoices, quotes, ISV, profits, and investment queries'),
('rep_lbl_fecha_inicio', 'Fecha Inicio', 'Start Date'),
('rep_lbl_fecha_fin', 'Fecha Fin', 'End Date'),
('rep_btn_generar', 'Generar Reportes', 'Generate Reports'),
('rep_tab_facturas', 'Facturas', 'Invoices'),
('rep_tab_cotizaciones', 'Cotizaciones', 'Quotes'),
('rep_tab_isv', 'ISV', 'Sales Tax'),
('rep_tab_ganancias', 'Ganancias', 'Profits'),
('rep_tab_inversion', 'Inversión', 'Investment'),
('rep_th_factura', 'Factura', 'Invoice'),
('rep_th_cliente', 'Cliente', 'Customer'),
('rep_th_fecha', 'Fecha', 'Date'),
('rep_th_total', 'Total', 'Total'),
('rep_ph_id_cliente', 'ID Cliente (opcional)', 'Customer ID (optional)'),
('rep_th_cotizacion', 'Cotización', 'Quote'),
('rep_th_monto', 'Monto', 'Amount'),
('rep_th_periodo_doc', 'Periodo / Documento', 'Period / Document'),
('rep_th_subtotal', 'Subtotal', 'Subtotal'),
('rep_th_isv_generado', 'ISV Generado', 'Generated Tax'),
('rep_th_detalle_prod', 'Detalle / Producto', 'Detail / Product'),
('rep_th_costo', 'Costo', 'Cost'),
('rep_th_venta', 'Venta', 'Sale'),
('rep_th_ganancia', 'Ganancia', 'Profit'),
('rep_th_cat_almacen', 'Categoría / Almacén', 'Category / Warehouse'),
('rep_th_tot_productos', 'Total Productos', 'Total Products'),
('rep_th_valor_inversion', 'Valor Inversión', 'Investment Value'),
('rep_msg_sin_registros', 'No hay registros para este filtro.', 'No records found for this filter.'),
('rep_msg_cargando', 'Cargando datos...', 'Loading data...'),
('rep_msg_error', 'Error al consultar datos. Revisa la consola.', 'Error fetching data. Check console.');

/*empleados*/
INSERT IGNORE INTO traducciones (clave, es, en) VALUES
('emp_titulo_modulo', 'Gestión de Empleados', 'Employee Management'),
('emp_subtitulo_modulo', 'Administración y registro de vendedores y personal del sistema', 'Administration and registration of sellers and system staff'),
('emp_btn_nuevo', 'Nuevo Empleado', 'New Employee'),
('emp_th_identidad', 'Identidad', 'ID Number'),
('emp_th_nombre', 'Nombre Completo', 'Full Name'),
('emp_th_correo', 'Correo Electrónico', 'Email Address'),
('emp_th_rol', 'Rol', 'Role'),
('emp_th_fecha', 'Fecha Registro', 'Registration Date'),
('emp_th_acciones', 'Acciones', 'Actions'),
('emp_msg_cargando', 'Cargando empleados...', 'Loading employees...'),
('emp_msg_sin_datos', 'No se encontraron empleados registrados.', 'No registered employees found.'),
('emp_msg_error_consulta', 'Error al consultar los empleados.', 'Error querying employees.'),
('emp_modal_nuevo', 'Registrar Empleado', 'Register Employee'),
('emp_modal_editar', 'Editar Empleado', 'Edit Employee'),
('emp_lbl_identidad', 'N° de Identidad', 'ID Number'),
('emp_lbl_nombre', 'Nombre Completo', 'Full Name'),
('emp_lbl_correo', 'Correo Electrónico', 'Email Address'),
('emp_lbl_rol', 'Rol del Sistema', 'System Role'),
('emp_opt_select_rol', 'Seleccione un rol...', 'Select a role...'),
('emp_opt_admin', 'Administrador', 'Administrator'),
('emp_opt_normal', 'Normal', 'Standard'),
('emp_lbl_clave', 'Contraseña', 'Password'),
('emp_lbl_confirmaclave', 'Confirmar Contraseña', 'Confirm Password'),
('emp_btn_cancelar', 'Cancelar', 'Cancel'),
('emp_btn_guardar', 'Guardar Empleado', 'Save Employee');

