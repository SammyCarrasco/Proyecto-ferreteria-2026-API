<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-cart-check text-primary me-2"></i><span data-i18n="ventas_titulo">Ventas</span></h4>
        <small class="text-muted"><span data-i18n="ventas_subtitulo">Facturar una cotización pendiente</span></small>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small"><span data-i18n="id_cotizacion_venta">ID Cotización (debe estar Pendiente)</span></label>
                <input type="number" class="form-control form-control-sm" id="venta-id-cotizacion">
            </div>
            <div class="col-md-3">
                <label class="form-label small"><span data-i18n="id_empleado_factura">ID Empleado que factura</span></label>
                <input type="number" class="form-control form-control-sm" id="venta-id-empleado" value="1">
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary btn-sm" id="btn-consultar-antes-facturar">
                    <i class="bi bi-search"></i> <span data-i18n="btn_ver_cotizacion">Ver cotización</span>
                </button>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-success btn-sm" id="btn-facturar" disabled>
                    <i class="bi bi-receipt-cutoff"></i> <span data-i18n="btn_facturar">Facturar</span>
                </button>
            </div>
        </div>

        <div id="venta-resumen" class="mt-3" style="display:none;">
            <table class="table table-sm">
                <tbody>
                    <tr><th style="width:200px;"><span data-i18n="th_cliente_venta">Cliente</span></th><td id="venta-cliente"></td></tr>
                    <tr><th><span data-i18n="th_estado_actual">Estado actual</span></th><td id="venta-estado"></td></tr>
                    <tr><th><span data-i18n="th_total_facturar">Total a facturar</span></th><td>L. <span id="venta-total">0.00</span></td></tr>
                </tbody>
            </table>
        </div>
         <div id="venta-factura-generada" class="mt-3" style="display:none;">
    <div class="alert alert-success">
        <h6 class="mb-2"><i class="bi bi-check-circle"></i> <span data-i18n="factura_generada">Factura generada</span></h6>
        <div class="row">
            <div class="col-md-3"><strong><span data-i18n="lbl_no_factura">No. Factura:</span></strong> <span id="fact-nro"></span></div>
            <div class="col-md-3"><strong><span data-i18n="lbl_subtotal_factura">Subtotal:</span></strong> L. <span id="fact-subtotal"></span></div>
            <div class="col-md-3"><strong><span data-i18n="lbl_isv_factura">ISV (15%):</span></strong> L. <span id="fact-isv"></span></div>
            <div class="col-md-3"><strong><span data-i18n="lbl_total_factura">Total:</span></strong> L. <span id="fact-total"></span></div>
        </div>
    </div>
</div>
        <div id="venta-mensaje" class="mt-2"></div>
    </div>
</div>

<script src="js/idiomas.js"></script>
<script>
(function () {
    function aplicarTraduccion() {
        if (typeof traducirPagina === 'function') {
            traducirPagina();
        }
    }

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
        $('#venta-mensaje').html('<div class="alert alert-' + tipo + ' py-2 small mb-0">' + texto + '</div>');
    }

    $('#btn-consultar-antes-facturar').on('click', function () {
        let id = $('#venta-id-cotizacion').val().trim();
        $('#venta-id-cotizacion').removeClass('is-invalid');

        if (!id) {
            $('#venta-id-cotizacion').addClass('is-invalid');
            mostrarMensaje('warning', 'Ingresa el ID de la cotización.');
            return;
        }
        if (parseInt(id) <= 0) {
            mostrarMensaje('warning', 'El ID de la cotización debe ser un número positivo.');
            return;
        }

        let $btn = $(this);
        let htmlOriginal = $btn.html();
        $btn.prop('disabled', true).text('Consultando...');

        apiCall('cotizacionDetalle/' + id, 'GET')
            .done(function (resp) {
                if (resp.status !== 'OK') {
                    $('#venta-resumen').hide();
                    $('#btn-facturar').prop('disabled', true);
                    mostrarMensaje('danger', 'La cotización #' + id + ' no existe.');
                    return;
                }
                let c = resp.message;
                $('#venta-cliente').text(c.cliente);
                $('#venta-estado').html(c.estado === 'Pendiente'
                    ? '<span class="badge bg-warning text-dark">Pendiente</span>'
                    : '<span class="badge bg-secondary">' + c.estado + '</span>');
                $('#venta-total').text(parseFloat(c.total).toFixed(2));
                $('#venta-resumen').show();

                let esPendiente = (c.estado === 'Pendiente');
                $('#btn-facturar').prop('disabled', !esPendiente);
                if (!esPendiente) {
                    mostrarMensaje('warning', 'Esta cotización ya no está Pendiente, no se puede facturar de nuevo.');
                } else {
                    $('#venta-mensaje').empty();
                }
            })
            .fail(function (xhr) {
                $('#venta-resumen').hide();
                $('#btn-facturar').prop('disabled', true);
                mostrarMensaje('danger', 'La cotización #' + id + ' no existe o no se pudo consultar.');
            })
            .always(function () {
                $btn.prop('disabled', false).html(htmlOriginal);
                aplicarTraduccion();
            });
    });

    $('#btn-facturar').on('click', function () {
        let idCotizacion = $('#venta-id-cotizacion').val().trim();
        let idEmpleado = $('#venta-id-empleado').val().trim();

        $('#venta-id-empleado').removeClass('is-invalid');
        if (!idEmpleado) {
            $('#venta-id-empleado').addClass('is-invalid');
            mostrarMensaje('warning', 'Debes ingresar el ID Empleado que factura.');
            return;
        }

        let $btn = $(this);
        let htmlOriginal = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Facturando...');

        apiCall('venta', 'POST', { id_cotizacion: idCotizacion, id_empleado: idEmpleado })
        .done(function (resp) {
        if (resp.status !== 'OK') {
            mostrarMensaje('danger', resp.message);
            $btn.prop('disabled', false).html(htmlOriginal);
            return;
        }
        mostrarMensaje('success', resp.message);
        $btn.prop('disabled', true).html(htmlOriginal);
        $('#venta-estado').html('<span class="badge bg-success">Facturada</span>');
                if (resp.data) {
                    $('#fact-nro').text(resp.data.nro_factura);
                    $('#fact-subtotal').text(parseFloat(resp.data.subtotal).toFixed(2));
                    $('#fact-isv').text(parseFloat(resp.data.isv).toFixed(2));
                    $('#fact-total').text(parseFloat(resp.data.total).toFixed(2));
                    $('#venta-factura-generada').show();
                }
                aplicarTraduccion();
            })
            .fail(function (xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : ('Error al facturar (' + xhr.status + ').');
                mostrarMensaje('danger', msg);
                $btn.prop('disabled', false).html(htmlOriginal);
                aplicarTraduccion();
            });
    });

    // Traducir al cargar y monitorear cambios de idioma
    aplicarTraduccion();
    let ultimoIdiomaVenta = localStorage.getItem('idioma');
    setInterval(function () {
        let actual = localStorage.getItem('idioma');
        if (actual !== ultimoIdiomaVenta) {
            ultimoIdiomaVenta = actual;
            aplicarTraduccion();
        }
    }, 500);
})();
</script>