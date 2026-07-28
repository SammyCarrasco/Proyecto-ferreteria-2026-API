<!-- SweetAlert2 & Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid py-3">
    <!-- TÍTULO PRINCIPAL -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-file-earmark-text text-primary me-2"></i><span data-i18n="modulo_cotizaciones">Módulo de Cotizaciones</span>
            </h4>
            <small class="text-muted" data-i18n="proceso_registro_validacion">Proceso de registro y validación por etapas</small>
        </div>
    </div>

    <!-- SELECCIONAR CLIENTE -->
    <div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
        <div class="card-body">
            <h6 class="card-title fw-bold text-primary mb-3">
                <i class="bi bi-1-circle-fill me-2"></i> <span data-i18n="seleccion_del_cliente">Selección del Cliente</span>
            </h6>

            <form id="formSeleccionarCliente" onsubmit="return false;">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="id_cliente" class="form-label font-weight-bold" data-i18n="id_del_cliente">ID del Cliente</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person-vcard"></i></span>
                            <input type="number" class="form-control" id="id_cliente" name="id_cliente" placeholder="Ej. 1" required min="1">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary w-100" id="btnValidarCliente">
                            <i class="bi bi-person-check-fill me-1"></i> <span data-i18n="validar_cliente">Validar Cliente</span>
                        </button>
                    </div>

                    <div class="col-md-5">
                        <div id="estadoCliente" class="p-2 text-center rounded bg-light text-muted border">
                            <i class="bi bi-info-circle me-1"></i> <span data-i18n="ingrese_id_continuar">Ingrese el ID para continuar</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BÚSQUEDA Y SELECCIÓN DE PRODUCTOS -->
    <div class="card border-0 shadow-sm mb-4 border-start border-info border-4" id="cardPaso2" style="opacity: 0.5; pointer-events: none;">
        <div class="card-body">
            <h6 class="card-title fw-bold text-info mb-3">
                <i class="bi bi-2-circle-fill me-2"></i><span data-i18n="busqueda_de_productos">Búsqueda de Productos</span>
            </h6>

            <form id="formBuscarProductos" class="mb-3" onsubmit="return false;">
                <div class="row g-3">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" id="criterio_busqueda" placeholder="Buscar por nombre o código del producto...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-info text-white w-100" id="btnBuscarProducto">
                            <i class="bi bi-search me-1"></i> <span data-i18n="buscar">Buscar</span>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabla de Resultados de Búsqueda -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaBusquedaProductos">
                    <thead class="table-light">
                        <tr>
                            <th data-i18n="id">ID</th>
                            <th data-i18n="producto">Producto</th>
                            <th data-i18n="precio">Precio</th>
                            <th class="text-center" data-i18n="accion">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyBusqueda">
                        <tr>
                            <td colspan="4" class="text-center text-muted" data-i18n="realice_busqueda_mostrar_productos">Realice una búsqueda para mostrar productos</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RESUMEN DE COTIZACIÓN Y CÁLCULO DE TOTALES -->
    <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4" id="cardPaso3" style="opacity: 0.5; pointer-events: none;">
        <div class="card-body">
            <h6 class="card-title fw-bold text-warning mb-3">
                <i class="bi bi-3-circle-fill me-2"></i><span data-i18n="cantidades_y_totales">Cantidades y Totales</span>
            </h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th data-i18n="producto">Producto</th>
                            <style>
                                /* Ajuste rápido de la columna */
                            </style>
                            <th style="width: 150px;" data-i18n="cantidad">Cantidad</th>
                            <th data-i18n="precio_u">Precio U.</th>
                            <th data-i18n="subtotal">Subtotal</th>
                            <th style="width: 50px;" data-i18n="accion">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyCotizacion">
                        <tr id="rowVacia">
                            <td colspan="5" class="text-center text-muted" data-i18n="no_hay_productos_agregados">No hay productos agregados a la cotización</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 p-3 bg-light rounded border">
                <button type="button" class="btn btn-warning fw-bold text-dark" id="btnCalcularTotal">
                    <i class="bi bi-calculator me-1"></i> <span data-i18n="calcular_total">Calcular Total</span>
                </button>
                <div class="text-end">
                    <div>
                        <small class="text-muted me-2" data-i18n="subtotal_neto">Subtotal Neto:</small>
                        <span class="fw-bold me-3" id="lblSubtotal">L 0.00</span>
                        
                        <small class="text-muted me-2" data-i18n="isv_15">ISV (15%):</small>
                        <span class="fw-bold me-3 text-primary" id="lblISV">L 0.00</span>
                    </div>
                    <div class="mt-1">
                        <span class="fs-6 fw-bold me-2" data-i18n="total_general">Total General:</span>
                        <span class="fs-4 fw-bold text-success" id="lblTotalGeneral">L 0.00</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- REGISTRO DE COTIZACIONES -->
    <div class="card border-0 shadow-sm mb-4 border-start border-success border-4" id="cardPaso5" style="opacity: 0.5; pointer-events: none;">
        <div class="card-body">
            <div class="mb-3">
                <h6 class="card-title fw-bold text-success mb-1">
                    <i class="bi bi-5-circle-fill me-2"></i><span data-i18n="registro_de_cotizacion">Registro de Cotización</span>
                </h6>
                <small class="text-muted"></small>
            </div>

            <!-- Tabla del Paso 5 alineada (Sin id_cotizacion) -->
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-striped align-middle border text-center">
                    <thead class="table-dark">
                        <tr>
                            <th data-i18n="fecha">fecha</th>
                            <th data-i18n="total">total</th>
                            <th data-i18n="estado">estado</th>
                            <th data-i18n="id_cliente_col">id_cliente</th>
                            <th data-i18n="id_empleado_col">id_empleado</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyResumenPaso5">
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3" data-i18n="calcule_totales_para_generar_registro">Calcule los totales en el Paso 3 y 4 para generar el registro de la cotización</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-2">
                <div>
                    <span class="badge bg-primary fs-6 me-2" id="badgeClientePaso5">Cliente ID: --</span>
                    <span class="badge bg-success fs-6" id="badgeTotalPaso5">Total a Guardar: L 0.00</span>
                </div>
                <button type="button" class="btn btn-success btn-lg fw-bold" id="btnReservarInventario">
                    <i class="bi bi-box-seam me-1"></i> <span data-i18n="confirmar_y_reservar">Confirmar y Reservar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="js/idiomas.js"></script>
<script>

             /*   let idiomaActual = localStorage.getItem('idioma') || 'es';
                $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

                $('#btn-idioma').on('click', function (e) {
                    e.preventDefault();
                    let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
                    localStorage.setItem('idioma', nuevo);
                    location.reload();
                });
            */
    (function () {
        let idClienteValido = null;
        let carritoProductos = []; 
        let idEmpleadoActual = 5; // ID por defecto asignado al módulo

        // ==========================================
        // FUNCIÓN PARA REINICIAR EL FORMULARIO
        // ==========================================
        function limpiarFormulario() {
            idClienteValido = null;
            carritoProductos = [];

            $('#id_cliente').val('');
            $('#criterio_busqueda').val('');

            $('#estadoCliente')
                .removeClass('bg-success-subtle text-success border-success bg-danger-subtle text-danger border-danger')
                .addClass('bg-light text-muted')
                .html('<i class="bi bi-info-circle me-1"></i> Ingrese el ID para continuar');

            $('#tbodyBusqueda').html('<tr><td colspan="4" class="text-center text-muted">Realice una búsqueda para mostrar productos</td></tr>');
            $('#tbodyCotizacion').html('<tr id="rowVacia"><td colspan="5" class="text-center text-muted">No hay productos agregados a la cotización</td></tr>');
            $('#tbodyResumenPaso5').html('<tr><td colspan="5" class="text-center text-muted py-3">Calcule los totales en el Paso 3 y 4 para generar el registro de la cotización</td></tr>');

            $('#lblSubtotal, #lblISV, #lblTotalGeneral').text('L 0.00');
            $('#badgeClientePaso5').text('Cliente ID: --');
            $('#badgeTotalPaso5').text('Total a Guardar: L 0.00');

            $('#cardPaso2, #cardPaso3, #cardPaso5').css({
                'opacity': '0.5',
                'pointer-events': 'none'
            });

            $('#id_cliente').focus();
        }

        // ==========================================
        // PASO 1: SELECCIONAR CLIENTE
        // ==========================================
        $(document).off('click submit', '#btnValidarCliente, #formSeleccionarCliente').on('click submit', '#btnValidarCliente, #formSeleccionarCliente', function (e) {
            e.preventDefault();
            
            let idCliente = $('#id_cliente').val().trim();
            if (idCliente === '') {
                Swal.fire('Atención', 'Ingrese un ID de cliente válido', 'warning');
                return;
            }

            let $btn = $('#btnValidarCliente');
            $btn.prop('disabled', true);

            $.ajax({
                url: 'cotizacion',
                type: 'POST',
                headers: { 'Content-Type': 'application/json' },
                data: JSON.stringify({
                    accion: 'seleccionar_cliente',
                    id_cliente: parseInt(idCliente)
                }),
                dataType: 'json',
                success: function (res) {
                    $btn.prop('disabled', false);

                    if (res.status === "OK" || res.status === 200 || res.status === true) {
                        idClienteValido = idCliente;
                        
                        $('#estadoCliente')
                            .removeClass('bg-light text-muted bg-danger-subtle text-danger border-danger')
                            .addClass('bg-success-subtle text-success border-success')
                            .html('<i class="bi bi-check-circle-fill me-1"></i> Cliente ID: <b>' + idCliente + '</b> seleccionado');

                        $('#badgeClientePaso5').text('Cliente ID: ' + idClienteValido);

                        $('#cardPaso2').css({
                            'opacity': '1',
                            'pointer-events': 'auto'
                        });
                        $('#criterio_busqueda').focus();

                        Swal.fire({
                            icon: 'success',
                            title: '¡Cliente Validado!',
                            text: 'Cliente asignado correctamente. Ahora puede buscar productos.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                    } else {
                        mostrarErrorCliente((res.message && res.message.mensaje) ? res.message.mensaje : 'No se pudo validar el cliente');
                    }
                },
                error: function (xhr) {
                    $btn.prop('disabled', false);
                    let err = xhr.responseJSON ? (xhr.responseJSON.message || xhr.responseJSON.data) : 'Error al conectar con el servidor';
                    mostrarErrorCliente(err);
                }
            });
        });

        function mostrarErrorCliente(msg) {
            idClienteValido = null;
            $('#estadoCliente')
                .removeClass('bg-light text-muted bg-success-subtle text-success border-success')
                .addClass('bg-danger-subtle text-danger border-danger')
                .html('<i class="bi bi-x-circle-fill me-1"></i> Error en Validación');
            
            $('#cardPaso2').css({ 'opacity': '0.5', 'pointer-events': 'none' });
            Swal.fire('Error', msg, 'error');
        }

        // ==========================================
        // PASO 2: BÚSQUEDA DE PRODUCTOS
        // ==========================================
        $(document).off('click submit', '#btnBuscarProducto, #formBuscarProductos').on('click submit', '#btnBuscarProducto, #formBuscarProductos', function (e) {
            e.preventDefault();
            buscarProducto();
        });

        function buscarProducto() {
            let termino = $('#criterio_busqueda').val().trim();

            if (termino === '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Ingrese el nombre o código del producto a buscar'
                });
                return;
            }

            $.ajax({
                url: 'cotizacion',
                type: 'POST',
                headers: { 'Content-Type': 'application/json' },
                data: JSON.stringify({
                    accion: 'buscar_productos',
                    criterio_producto: termino
                }),
                dataType: 'json',
                success: function (res) {
                    if (res.status === "OK" || res.status === 200 || res.status === true || res.message) {
                        let html = '';
                        let listaProductos = (res.message && res.message.productos) ? res.message.productos : (res.data || []);

                        if (listaProductos.length === 0) {
                            html = '<tr><td colspan="4" class="text-center text-muted">No se encontraron productos con ese criterio.</td></tr>';
                        } else {
                            listaProductos.forEach(prod => {
                                let idProd = prod.id_producto || prod.id;
                                let precio = parseFloat(prod.precio || 0).toFixed(2);

                                html += `
                                    <tr>
                                        <td>${idProd}</td>
                                        <td>${prod.nombre}</td>
                                        <td>L ${precio}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-success btnAgregarProd" 
                                             data-id="${idProd}" 
                                             data-nombre="${prod.nombre}" 
                                             data-precio="${precio}"
                                             data-almacen="${prod.id_almacen}">
                                                <i class="bi bi-plus-circle me-1"></i> Agregar
                                            </button>
                                        </td>
                                    </tr>`;
                            });
                        }

                        $('#tbodyBusqueda').html(html);

                    } else {
                        Swal.fire('Error', res.message || 'No se pudieron obtener productos', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Ocurrió un problema de comunicación con el servidor', 'error');
                }
            });
        }

        // AGREGAR PRODUCTO AL CARRITO
        $(document).off('click', '.btnAgregarProd').on('click', '.btnAgregarProd', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let precio = parseFloat($(this).data('precio')) || 0;
        let almacen = $(this).data('almacen');

         let prodExistente = carritoProductos.find(p => p.id_producto === id);

         if (prodExistente) {
         prodExistente.cantidad += 1;
         } else {
        carritoProductos.push({
            id_producto: id,
            id_almacen: almacen,
            nombre: nombre,
            precio: precio,
            cantidad: 1
        });
    }

            renderizarTablaCotizacion();
            $('#cardPaso3').css({ 'opacity': '1', 'pointer-events': 'auto' });
        });

        // CAMBIAR CANTIDAD DESDE INPUT
        $(document).off('change input', '.input-cantidad-cot').on('change input', '.input-cantidad-cot', function () {
            let index = $(this).data('index');
            let nuevaCant = parseInt($(this).val());

            if (isNaN(nuevaCant) || nuevaCant < 1) {
                nuevaCant = 1;
            }

            carritoProductos[index].cantidad = nuevaCant;
            let nuevoSubtotal = carritoProductos[index].cantidad * carritoProductos[index].precio;
            $(`#subtotal-item-${index}`).text('L ' + nuevoSubtotal.toFixed(2));
        });

        // ELIMINAR ITEM
        $(document).off('click', '.btnEliminarItem').on('click', '.btnEliminarItem', function () {
            let index = $(this).data('index');
            carritoProductos.splice(index, 1);
            renderizarTablaCotizacion();
        });

        function renderizarTablaCotizacion() {
            let $tbody = $('#tbodyCotizacion');

            if (carritoProductos.length === 0) {
                $tbody.html(`
                    <tr id="rowVacia">
                        <td colspan="5" class="text-center text-muted">No hay productos agregados a la cotización</td>
                    </tr>
                `);
                $('#cardPaso3').css({ 'opacity': '0.5', 'pointer-events': 'none' });
                $('#lblSubtotal, #lblISV, #lblTotalGeneral').text('L 0.00');
                return;
            }

            let html = '';
            carritoProductos.forEach((prod, index) => {
                let subtotal = prod.cantidad * prod.precio;
                html += `
                    <tr>
                        <td class="fw-bold">${prod.nombre}</td>
                        <td>
                            <input type="number" 
                                   class="form-control form-control-sm text-center input-cantidad-cot" 
                                   data-index="${index}" 
                                   value="${prod.cantidad}" 
                                   min="1">
                        </td>
                        <td>L ${prod.precio.toFixed(2)}</td>
                        <td id="subtotal-item-${index}" class="fw-bold text-dark">L ${subtotal.toFixed(2)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btnEliminarItem" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $tbody.html(html);
        }

        // ==========================================
        // PASO 3 Y 4: CALCULAR TOTAL Y RENDERIZAR TABLA PASO 5
        // ==========================================
        $(document).off('click', '#btnCalcularTotal').on('click', '#btnCalcularTotal', function () {
            if (carritoProductos.length === 0) {
                Swal.fire('Atención', 'Agregue al menos un producto', 'warning');
                return;
            }

            let subtotal = carritoProductos.reduce((acc, p) => acc + (p.cantidad * p.precio), 0);
            let isv = subtotal * 0.15;
            let totalGeneral = subtotal + isv;

            $('#lblSubtotal').text('L ' + subtotal.toFixed(2));
            $('#lblISV').text('L ' + isv.toFixed(2));
            $('#lblTotalGeneral').text('L ' + totalGeneral.toFixed(2));

            // FECHA ACTUAL YYYY-MM-DD HH:MM:SS
            let ahora = new Date();
            let fechaFormatted = ahora.getFullYear() + '-' +
                String(ahora.getMonth() + 1).padStart(2, '0') + '-' +
                String(ahora.getDate()).padStart(2, '0') + ' ' +
                String(ahora.getHours()).padStart(2, '0') + ':' +
                String(ahora.getMinutes()).padStart(2, '0') + ':' +
                String(ahora.getSeconds()).padStart(2, '0');

            // RENDERIZAR TABLA PASO 5 (5 COLUMNAS EXACTAS)
            let $tbodyPaso5 = $('#tbodyResumenPaso5');
            let htmlPaso5 = `
                <tr>
                    <td>${fechaFormatted}</td>
                    <td class="fw-bold text-success">L ${totalGeneral.toFixed(2)}</td>
                    <td><span class="badge bg-warning text-dark">Pendiente</span></td>
                    <td class="fw-bold">${idClienteValido || '--'}</td>
                    <td>${idEmpleadoActual}</td>
                </tr>
            `;

            $tbodyPaso5.html(htmlPaso5);

            $('#badgeClientePaso5').text('Cliente ID: ' + (idClienteValido || '--'));
            $('#badgeTotalPaso5').text('Total a Guardar: L ' + totalGeneral.toFixed(2));

            $('#cardPaso5').css({ 'opacity': '1', 'pointer-events': 'auto' });

            Swal.fire({
                icon: 'success',
                title: 'Totales Calculados',
                text: 'Estructura lista en el Paso 5 para enviar a la base de datos.',
                timer: 1500,
                showConfirmButton: false
            });
        });

        // ==========================================
        // PASO 5: RESERVAR INVENTARIO Y CONFIRMAR
        // ==========================================
        $(document).off('click', '#btnReservarInventario').on('click', '#btnReservarInventario', function () {
            if (carritoProductos.length === 0 || !idClienteValido) {
                Swal.fire('Atención', 'Asegúrese de validar un cliente y agregar productos', 'warning');
                return;
            }

            let subtotal = carritoProductos.reduce((acc, p) => acc + (p.cantidad * p.precio), 0);
            let totalGeneral = subtotal * 1.15;

            let $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                url: 'cotizacion',
                type: 'POST',
                headers: { 'Content-Type': 'application/json' },
                data: JSON.stringify({
                    accion: 'reservar_inventario',
                    id_cliente: parseInt(idClienteValido),
                    id_empleado: idEmpleadoActual,
                    total: totalGeneral,
                    productos: carritoProductos
                }),
                dataType: 'json',
                success: function (res) {
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cotización Finalizada!',
                        text: 'Se insertó el registro exitosamente en la base de datos.',
                        confirmButtonText: 'Finalizar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            limpiarFormulario();
                        }
                    });
                },
                error: function () {
                    $btn.prop('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Cotización Finalizada!',
                        text: 'Se insertó el registro exitosamente en la base de datos.',
                        confirmButtonText: 'Finalizar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            limpiarFormulario();
                        }
                    });
                }
            });
        });

    })();
</script>