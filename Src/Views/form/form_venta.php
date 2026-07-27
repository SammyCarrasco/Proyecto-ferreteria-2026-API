<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-cart-check text-primary me-2"></i>Ventas</h4>
        <small class="text-muted">Facturar una cotización pendiente</small>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">ID Cotización (debe estar Pendiente)</label>
                <input type="number" class="form-control form-control-sm" id="venta-id-cotizacion">
            </div>
            <div class="col-md-3">
                <label class="form-label small">ID Empleado que factura</label>
                <input type="number" class="form-control form-control-sm" id="venta-id-empleado" value="1">
            </div>
            <div class="col-md-auto">
                <button class="btn btn-primary btn-sm" id="btn-consultar-antes-facturar">
                    <i class="bi bi-search"></i> Ver cotización
                </button>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-success btn-sm" id="btn-facturar" disabled>
                    <i class="bi bi-receipt-cutoff"></i> Facturar
                </button>
            </div>
        </div>

        <div id="venta-resumen" class="mt-3" style="display:none;">
            <table class="table table-sm">
                <tbody>
                    <tr><th style="width:200px;">Cliente</th><td id="venta-cliente"></td></tr>
                    <tr><th>Estado actual</th><td id="venta-estado"></td></tr>
                    <tr><th>Total a facturar</th><td>L. <span id="venta-total">0.00</span></td></tr>
                </tbody>
            </table>
        </div>
         <div id="venta-factura-generada" class="mt-3" style="display:none;">
    <div class="alert alert-success">
        <h6 class="mb-2"><i class="bi bi-check-circle"></i> Factura generada</h6>
        <div class="row">
            <div class="col-md-3"><strong>No. Factura:</strong> <span id="fact-nro"></span></div>
            <div class="col-md-3"><strong>Subtotal:</strong> L. <span id="fact-subtotal"></span></div>
            <div class="col-md-3"><strong>ISV (15%):</strong> L. <span id="fact-isv"></span></div>
            <div class="col-md-3"><strong>Total:</strong> L. <span id="fact-total"></span></div>
        </div>
    </div>
</div>
        <div id="venta-mensaje" class="mt-2"></div>
    </div>
</div>

<script>
(function () {
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
        let id = $('#venta-id-cotizacion').val();
        if (!id) { mostrarMensaje('warning', 'Ingresa el ID de la cotización.'); return; }

        apiCall('cotizacionDetalle/' + id, 'GET')
            .done(function (resp) {
                if (resp.status !== 'OK') {
                    mostrarMensaje('danger', resp.message);
                    $('#venta-resumen').hide();
                    $('#btn-facturar').prop('disabled', true);
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
                mostrarMensaje('danger', 'No se pudo consultar la cotización (' + xhr.status + ').');
            });
    });

    $('#btn-facturar').on('click', function () {
        let idCotizacion = $('#venta-id-cotizacion').val();
        let idEmpleado = $('#venta-id-empleado').val();

        apiCall('venta', 'POST', { id_cotizacion: idCotizacion, id_empleado: idEmpleado })
            .done(function (resp) {
                if (resp.status !== 'OK') {
                    mostrarMensaje('danger', resp.message);
                    return;
                }
                mostrarMensaje('success', resp.message);
                $('#btn-facturar').prop('disabled', true);
                $('#venta-estado').html('<span class="badge bg-success">Facturada</span>');

                if (resp.data) {
                    $('#fact-nro').text(resp.data.nro_factura);
                    $('#fact-subtotal').text(parseFloat(resp.data.subtotal).toFixed(2));
                    $('#fact-isv').text(parseFloat(resp.data.isv).toFixed(2));
                    $('#fact-total').text(parseFloat(resp.data.total).toFixed(2));
                    $('#venta-factura-generada').show();
                }
            })
            .fail(function (xhr) {
                let msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : ('Error al facturar (' + xhr.status + ').');
                mostrarMensaje('danger', msg);
            });
    });
})();
</script>