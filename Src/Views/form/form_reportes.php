<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-file-earmark-bar-graph-fill text-primary me-2"></i>Módulo de Reportes
            </h4>
            <small class="text-muted">Consulta de facturas, cotizaciones, ISV, ganancias e inversión</small>
        </div>
    </div>

    <!-- Filtro de Fechas Global -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="formFiltrosReporte" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label fw-bold">Fecha Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label fw-bold">Fecha Fin</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="button" id="btnFiltrar" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Generar Reportes
                    </button>
                    <button type="button" id="btnLimpiar" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pestañas (Tabs) Bootstrap -->
    <ul class="nav nav-pills mb-3" id="reportesTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="tab-facturas" data-bs-toggle="pill" data-bs-target="#sec-facturas" type="button">
                <i class="bi bi-receipt me-1"></i> Facturas
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-cotizaciones" data-bs-toggle="pill" data-bs-target="#sec-cotizaciones" type="button">
                <i class="bi bi-journal-text me-1"></i> Cotizaciones
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-isv" data-bs-toggle="pill" data-bs-target="#sec-isv" type="button">
                <i class="bi bi-percent me-1"></i> ISV
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-ganancias" data-bs-toggle="pill" data-bs-target="#sec-ganancias" type="button">
                <i class="bi bi-currency-dollar me-1"></i> Ganancias
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="tab-inversion" data-bs-toggle="pill" data-bs-target="#sec-inversion" type="button">
                <i class="bi bi-box-seam me-1"></i> Inversión
            </button>
        </li>
    </ul>

    <!-- Contenedor de Tablas -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="tab-content" id="reportesTabsContent">
                
                <!-- Facturas -->
                <div class="tab-pane fade show active" id="sec-facturas">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaFacturas">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Factura</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Cotizaciones -->
                <div class="tab-pane fade" id="sec-cotizaciones">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="number" id="buscarIdCliente" class="form-control" placeholder="ID Cliente (opcional)">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaCotizaciones">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Cotización</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- ISV -->
                <div class="tab-pane fade" id="sec-isv">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaISV">
                            <thead class="table-light">
                                <tr>
                                    <th>Periodo / Documento</th>
                                    <th>Subtotal</th>
                                    <th>ISV Generado</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Ganancias -->
                <div class="tab-pane fade" id="sec-ganancias">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaGanancias">
                            <thead class="table-light">
                                <tr>
                                    <th>Detalle / Producto</th>
                                    <th>Costo</th>
                                    <th>Venta</th>
                                    <th>Ganancia</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Inversión -->
                <div class="tab-pane fade" id="sec-inversion">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="tablaInversion">
                            <thead class="table-light">
                                <tr>
                                    <th>Categoría / Almacén</th>
                                    <th>Total Productos</th>
                                    <th>Valor Inversión</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const token = localStorage.getItem('token');

    // Construir parámetros de búsqueda por fecha e id_cliente
    function buildParams(extra = {}) {
        const fi = $('#fecha_inicio').val();
        const ff = $('#fecha_fin').val();
        let query = [];

        if (fi) query.push(`fecha_inicio=${fi}`);
        if (ff) query.push(`fecha_fin=${ff}`);
        
        for (let k in extra) {
            if (extra[k]) query.push(`${k}=${extra[k]}`);
        }

        return query.length > 0 ? '?' + query.join('&') : '';
    }

    // Renderizar datos específicos según cada endpoint para coincidir con sus HTML <th>
    function renderTabla(idTabla, data) {
        let html = '';

        if (!Array.isArray(data) || data.length === 0) {
            $(`#${idTabla} tbody`).html('<tr><td colspan="10" class="text-center py-4 text-muted">No hay registros para este filtro.</td></tr>');
            return;
        }

        data.forEach((row, i) => {
            html += '<tr>';
            
            if (idTabla === 'tablaFacturas') {
                const fecha = row.fecha ? row.fecha.split(' ')[0] : '-';
                const total = row.total ? `L. ${parseFloat(row.total).toFixed(2)}` : 'L. 0.00';
                html += `<td>${i + 1}</td>`;
                html += `<td><strong>${row.nro_factura || '-'}</strong></td>`;
                html += `<td>${row.cliente || '-'}</td>`;
                html += `<td>${fecha}</td>`;
                html += `<td>${total}</td>`;
            } else {
                // Renderizado genérico adaptativo para otras tablas
                Object.values(row).forEach((val, idx) => {
                    // Si el valor parece un número decimal, formatear a Lempiras
                    if (typeof val === 'string' && !isNaN(val) && val.includes('.')) {
                        val = `L. ${parseFloat(val).toFixed(2)}`;
                    }
                    html += `<td>${val ?? '-'}</td>`;
                });
            }

            html += '</tr>';
        });

        $(`#${idTabla} tbody`).html(html);
    }

    // Función principal AJAX
    function consultarEndpoint(subruta, idTabla, extra = {}) {
        $(`#${idTabla} tbody`).html('<tr><td colspan="10" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando datos...</td></tr>');
        
        $.ajax({
            url: `reportes/${subruta}${buildParams(extra)}`,
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            dataType: 'json',
            success: function (res) {
                // CORRECCIÓN CLAVE: Buscar primero en res.message que es donde tu API manda los datos
                let list = [];
                if (Array.isArray(res.message)) {
                    list = res.message;
                } else if (Array.isArray(res.data)) {
                    list = res.data;
                } else if (Array.isArray(res)) {
                    list = res;
                }

                renderTabla(idTabla, list);
            },
            error: function (xhr) {
                console.error(`Error en reportes/${subruta}:`, xhr);
                $(`#${idTabla} tbody`).html('<tr><td colspan="10" class="text-center py-4 text-danger">Error al consultar datos. Revisa la consola.</td></tr>');
            }
        });
    }

    // Funciones individuales de carga
    function cargarFacturas() { consultarEndpoint('facturas', 'tablaFacturas'); }
    function cargarCotizaciones() {
        const idCli = $('#buscarIdCliente').val();
        if (idCli) {
            consultarEndpoint('cotizaciones-cliente', 'tablaCotizaciones', { id_cliente: idCli });
        } else {
            consultarEndpoint('cotizaciones', 'tablaCotizaciones');
        }
    }
    function cargarISV() { consultarEndpoint('isv', 'tablaISV'); }
    function cargarGanancias() { consultarEndpoint('ganancias', 'tablaGanancias'); }
    function cargarInversion() { consultarEndpoint('inversion', 'tablaInversion'); }

    // Carga inicial
    cargarFacturas();

    // Evento manual para alternar pestañas
    $(document).off('click', '#reportesTabs button').on('click', '#reportesTabs button', function (e) {
        e.preventDefault();
        
        $('#reportesTabs button').removeClass('active');
        $('.tab-pane').removeClass('show active');

        $(this).addClass('active');
        const target = $(this).data('bs-target');
        $(target).addClass('show active');

        if (target === '#sec-facturas') cargarFacturas();
        if (target === '#sec-cotizaciones') cargarCotizaciones();
        if (target === '#sec-isv') cargarISV();
        if (target === '#sec-ganancias') cargarGanancias();
        if (target === '#sec-inversion') cargarInversion();
    });

    // Evento Botón Generar Reportes
    $(document).off('click', '#btnFiltrar').on('click', '#btnFiltrar', function (e) {
        e.preventDefault();
        const activeTarget = $('#reportesTabs button.active').data('bs-target');
        if (activeTarget === '#sec-facturas') cargarFacturas();
        if (activeTarget === '#sec-cotizaciones') cargarCotizaciones();
        if (activeTarget === '#sec-isv') cargarISV();
        if (activeTarget === '#sec-ganancias') cargarGanancias();
        if (activeTarget === '#sec-inversion') cargarInversion();
    });

    // Evento Botón Limpiar
    $(document).off('click', '#btnLimpiar').on('click', '#btnLimpiar', function (e) {
        e.preventDefault();
        $('#fecha_inicio, #fecha_fin, #buscarIdCliente').val('');
        $('#btnFiltrar').trigger('click');
    });
})();
</script>