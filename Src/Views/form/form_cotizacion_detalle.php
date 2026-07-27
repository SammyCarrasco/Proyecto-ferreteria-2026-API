<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-text text-primary me-2"></i><span data-i18n="cotizaciones_detalle">Cotizaciones — Detalle</span></h4>
        <small class="text-muted"><span data-i18n="crear_buscar_modificar_productos_cotizacion"> Crear, buscar y modificar productos de una cotización</span></small>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end mb-2">
            <div class="col-md-3">
                <label class="form-label small"><span data-i18n="id_cliente">ID Cliente</span></label>
                <input type="number" class="form-control form-control-sm" id="cot-id-cliente" value="1">
            </div>
            <div class="col-md-3">
                <label class="form-label small"><span data-i18n="id_empleado">ID Empleado</span></label>
                <input type="number" class="form-control form-control-sm" id="cot-id-empleado" value="1">
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary btn-sm" id="btn-crear-cotizacion">
                    <i class="bi bi-plus-circle"></i><span data-i18n="nueva_cotizacion"> Nueva cotización</span>
                </button>
            </div>
            <div class="col-md-3">
                <label class="form-label small"><span data-i18n="cargar_cotizacion_existente_id">Cargar cotización existente (ID)</span></label>
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" id="cot-id-buscar">
                    <button class="btn btn-outline-secondary" id="btn-buscar-cotizacion">Cargar</button>
                </div>
            </div>
        </div>
        <div id="cot-mensaje"></div>
    </div>
</div>

<div class="card mb-3" id="cot-panel-activa" style="display:none;">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt"></i><span data-i18n="cotizacion"> Cotización</span> #<span id="cot-id-actual"></span>
            — <span id="cot-cliente-nombre" class="text-muted"></span>
            <span class="badge" id="cot-estado-badge"></span>
        </span>
        <strong>Total: L. <span id="cot-total">0.00</span></strong>
    </div>
    <div class="card-body">

        <div class="row g-2 align-items-end mb-3 border-bottom pb-3">
            <div class="col-md-2">
                <label class="form-label small"><span data-i18n="id_producto">ID Producto</span></label>
                <input type="number" class="form-control form-control-sm" id="det-id-producto" value="1">
            </div>
            <div class="col-md-2">
                <label class="form-label small"><span data-i18n="id_almacen">ID Almacén</span></label>
                <input type="number" class="form-control form-control-sm" id="det-id-almacen" value="1">
            </div>
            <div class="col-md-2">
                <label class="form-label small"><span data-i18n="cantidad">Cantidad</span></label>
                <input type="number" class="form-control form-control-sm" id="det-cantidad" value="1" min="1">
            </div>
            <div class="col-md-2">
                <label class="form-label small"><span data-i18n="precio_unitario">Precio unitario</span></label>
                <input type="number" step="0.01" class="form-control form-control-sm" id="det-precio" value="150.00">
            </div>
            <div class="col-md-auto">
                <button class="btn btn-success btn-sm" id="btn-agregar-producto">
                    <i class="bi bi-cart-plus"></i><span data-i18n="agregar_a_la_cotizacion"> Agregar a la cotización</span>
                </button>
            </div>
        </div>

        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th><span data-i18n="producto">Producto</span></th>
                    <th><span data-i18n="almacen">Almacén</span></th>
                    <th style="width:110px;"><span data-i18n="cantidad">Cantidad</span></th>
                    <th><span data-i18n="precio_unit">Precio unit.</span></th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cot-detalle-body">
                <tr><td colspan="6" class="text-center text-muted"><span data-i18n="sin_productos_agregados_todavia">Sin productos agregados todavía.</td></span></tr>
            </tbody>
        </table>
        <div class="alert alert-warning py-2 small" id="cot-detalle-vacio-aviso" style="display:none;"><span data-i18n="cotizacion_ya_facturada_no_modificar">
            Esta cotización ya fue facturada — no se puede modificar.</span> 
        </div>
    </div>
</div>
<script src="js/idiomas.js"> </script>
<script>
(function () {
    let idCotizacionActual = null;

    
    function apiCall(route, method, data) {
        return $.ajax({
            url: route,
            method: method || 'GET',
            contentType: 'application/json',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
            data: data ? JSON.stringify(data) : undefined,
            dataType: 'json'
        });
    }

    function mostrarMensaje(tipo, texto) {
        $('#cot-mensaje').html('<div class="alert alert-' + tipo + ' py-2 small mb-0 mt-2">' + texto + '</div>');
    }

    function badgeEstado(estado) {
        let clase = estado === 'Pendiente' ? 'bg-warning text-dark' : (estado === 'Facturada' ? 'bg-success' : 'bg-secondary');
        return '<span class="badge ' + clase + '">' + estado + '</span>';
    }

    function cargarCotizacion(id) {
        apiCall('cotizacionDetalle/' + id, 'GET')
            .done(function (resp) {
                if (resp.status !== 'OK') {
                    mostrarMensaje('danger', resp.message);
                    return;
                }
                let c = resp.message;
                idCotizacionActual = c.id_cotizacion;

                $('#cot-panel-activa').show();
                $('#cot-id-actual').text(c.id_cotizacion);
                $('#cot-cliente-nombre').text(c.cliente);
                $('#cot-estado-badge').html(badgeEstado(c.estado));
                $('#cot-total').text(parseFloat(c.total).toFixed(2));

                let esPendiente = (c.estado === 'Pendiente');
                $('#btn-agregar-producto').prop('disabled', !esPendiente);
                $('#cot-detalle-vacio-aviso').toggle(!esPendiente);

                let filas = '';
                if (!c.detalle || c.detalle.length === 0) {
                    filas = '<tr><td colspan="6" class="text-center text-muted">Sin productos agregados todavía.</td></tr>';
                } else {
                    c.detalle.forEach(function (d) {
                        filas += '<tr data-id-detalle="' + d.id_detalle + '">' +
                            '<td>' + d.producto + '</td>' +
                            '<td>' + d.id_almacen + '</td>' +
                            '<td><div class="input-group input-group-sm">' +
                                '<input type="number" min="1" class="form-control cant-input" value="' + d.cantidad + '" ' + (esPendiente ? '' : 'disabled') + '>' +
                                (esPendiente ? '<button class="btn btn-outline-primary btn-guardar-cant"><i class="bi bi-check"></i></button>' : '') +
                            '</div></td>' +
                            '<td>L. ' + parseFloat(d.precio_unitario).toFixed(2) + '</td>' +
                            '<td>L. ' + parseFloat(d.subtotal).toFixed(2) + '</td>' +
                            '<td>' + (esPendiente ? '<button class="btn btn-sm btn-outline-danger btn-quitar"><i class="bi bi-trash"></i></button>' : '') + '</td>' +
                            '</tr>';
                    });
                }
                $('#cot-detalle-body').html(filas);
            })
            .fail(function (xhr) {
                mostrarMensaje('danger', 'No se pudo cargar la cotización (' + xhr.status + ').');
            });
    }

    $('#btn-crear-cotizacion').on('click', function () {
        let idCliente = $('#cot-id-cliente').val();
        let idEmpleado = $('#cot-id-empleado').val();

        apiCall('cotizacionDetalle/nueva', 'POST', { id_cliente: idCliente, id_empleado: idEmpleado })
            .done(function (resp) {
                if (resp.status !== 'OK') {
                    mostrarMensaje('danger', resp.message);
                    return;
                }
                mostrarMensaje('success', 'Cotización #' + resp.message.id_cotizacion + ' creada (Pendiente).');
                $('#cot-id-buscar').val(resp.message.id_cotizacion);
                cargarCotizacion(resp.message.id_cotizacion);
            })
            .fail(function (xhr) {
                mostrarMensaje('danger', 'Error al crear la cotización (' + xhr.status + ').');
            });
    });

    $('#btn-buscar-cotizacion').on('click', function () {
        let id = $('#cot-id-buscar').val();
        if (!id) { mostrarMensaje('warning', 'Ingresa un ID de cotización.'); return; }
        cargarCotizacion(id);
    });

    $('#btn-agregar-producto').on('click', function () {
        if (!idCotizacionActual) return;

        apiCall('cotizacionDetalle', 'POST', {
            id_cotizacion: idCotizacionActual,
            id_producto: $('#det-id-producto').val(),
            id_almacen: $('#det-id-almacen').val(),
            cantidad: $('#det-cantidad').val(),
            precio_unitario: $('#det-precio').val()
        }).done(function (resp) {
            if (resp.status !== 'OK') { mostrarMensaje('danger', resp.message); return; }
            mostrarMensaje('success', resp.message);
            cargarCotizacion(idCotizacionActual);
        }).fail(function (xhr) {
            let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : ('Error al agregar el producto (' + xhr.status + ').');
            mostrarMensaje('danger', msg);
        });
    });

    $('#cot-detalle-body').on('click', '.btn-quitar', function () {
        let idDetalle = $(this).closest('tr').data('id-detalle');
        apiCall('cotizacionDetalle/' + idDetalle, 'DELETE')
            .done(function (resp) {
                if (resp.status !== 'OK') { mostrarMensaje('danger', resp.message); return; }
                mostrarMensaje('success', resp.message);
                cargarCotizacion(idCotizacionActual);
            })
            .fail(function (xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : ('Error al quitar el producto (' + xhr.status + ').');
                mostrarMensaje('danger', msg);
            });
    });

    $('#cot-detalle-body').on('click', '.btn-guardar-cant', function () {
        let fila = $(this).closest('tr');
        let idDetalle = fila.data('id-detalle');
        let nuevaCantidad = fila.find('.cant-input').val();

        apiCall('cotizacionDetalle', 'PUT', { id_detalle: idDetalle, cantidad: nuevaCantidad })
            .done(function (resp) {
                if (resp.status !== 'OK') { mostrarMensaje('danger', resp.message); return; }
                mostrarMensaje('success', resp.message);
                cargarCotizacion(idCotizacionActual);
            })
            .fail(function (xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : ('Error al modificar la cantidad (' + xhr.status + ').');
                mostrarMensaje('danger', msg);
            });
    });
})();
</script>