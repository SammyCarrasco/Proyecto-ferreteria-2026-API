<!-- Cargar SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-box-seam text-primary me-2"></i>Gestión de Inventario</h4>
            <small class="text-muted">Control de productos en almacenes</small>
        </div>
        <button class="btn btn-primary" id="btnNuevoInventario">
            <i class="bi bi-plus-circle me-1"></i> Asociar Producto
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Almacén</th>
                            <th>Stock Disponible</th>
                            <th>Stock Reservado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyInventario">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                Cargando inventario...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Inventario -->
<div class="modal fade" id="modalInventario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInventarioLabel"><i class="bi bi-plus-circle me-2"></i>Asociar Producto</h5>
                <button type="button" class="btn-close btn-close-white" id="btnCerrarModalInventario"></button>
            </div>
            <form id="formInventario">
                <div class="modal-body">
                    <input type="hidden" id="id_inventario" name="id_inventario">

                    <div class="mb-3">
                        <label for="id_producto" class="form-label">ID Producto</label>
                        <input type="number" class="form-control" id="id_producto" name="id_producto" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_almacen" class="form-label">ID Almacén</label>
                        <input type="number" class="form-control" id="id_almacen" name="id_almacen" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock_disponible" class="form-label">Stock Disponible</label>
                        <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock_reservado" class="form-label">Stock Reservado</label>
                        <input type="number" class="form-control" id="stock_reservado" name="stock_reservado" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="btnCancelarInventario">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarInventario">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    let inventarioData = [];

    function abrirModalInventario() {
        $('#modalInventario').css('display', 'block').addClass('show');
        $('body').addClass('modal-open');
    }

    function cerrarModalInventario() {
        $('#modalInventario').css('display', 'none').removeClass('show');
        $('body').removeClass('modal-open');
    }

    // 1. LISTAR INVENTARIO
    function listarInventario() {
        $.ajax({
            url: 'inventario',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
            dataType: 'json',
            success: function (response) {
                inventarioData = response.data || response;
                let html = '';
                if (Array.isArray(inventarioData) && inventarioData.length > 0) {
                    inventarioData.forEach((inv, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${inv.producto || inv.id_producto}</td>
                                <td>${inv.almacen || inv.id_almacen}</td>
                                <td>${inv.stock_disponible}</td>
                                <td>${inv.stock_reservado}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1 btn-editar" data-id="${inv.id_inventario}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar" data-producto="${inv.id_producto}" data-almacen="${inv.id_almacen}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron registros de inventario.</td></tr>';
                }
                $('#tbodyInventario').html(html);
            },
            error: function () {
                $('#tbodyInventario').html('<tr><td colspan="6" class="text-center text-danger">Error al consultar inventario.</td></tr>');
            }
        });
    }
    listarInventario();

    // 2. NUEVO
    $(document).on('click', '#btnNuevoInventario', function () {
        $('#formInventario')[0].reset();
        $('#id_inventario').val('');
        $('#modalInventarioLabel').html('Asociar Producto');
        abrirModalInventario();
    });

    // 3. CERRAR
    $(document).on('click', '#btnCancelarInventario, #btnCerrarModalInventario', cerrarModalInventario);

    // 4. EDITAR
    $(document).on('click', '.btn-editar', function () {
        let id = $(this).data('id');
        let inv = inventarioData.find(i => i.id_inventario == id);
        if (inv) {
            $('#id_inventario').val(inv.id_inventario);
            $('#id_producto').val(inv.id_producto);
            $('#id_almacen').val(inv.id_almacen);
            $('#stock_disponible').val(inv.stock_disponible);
            $('#stock_reservado').val(inv.stock_reservado);
            $('#modalInventarioLabel').html('Editar Inventario');
            abrirModalInventario();
        }
    });

    // 5. GUARDAR (POST/PUT)
    $(document).on('submit', '#formInventario', function (e) {
        e.preventDefault();
        let id = $('#id_inventario').val();
        let payload = {
            id_producto: $('#id_producto').val(),
            id_almacen: $('#id_almacen').val(),
            stock_disponible: $('#stock_disponible').val(),
            stock_reservado: $('#stock_reservado').val()
        };
        let urlDestino = id ? 'inventario/' + id : 'inventario';
        let metodo = id ? 'PUT' : 'POST';

        $.ajax({
            url: urlDestino,
            type: metodo,
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(payload),
            success: function () {
                cerrarModalInventario();
                listarInventario();
                Swal.fire('Éxito', id ? 'Inventario actualizado.' : 'Producto asociado al almacén.', 'success');
            },
            error: function (xhr) {
                let msg = 'No se pudo guardar el inventario.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // 6. ELIMINAR (DELETE con clave compuesta)
    $(document).on('click', '.btn-eliminar', function () {
        let id_producto = $(this).data('producto');
        let id_almacen = $(this).data('almacen');

        Swal.fire({
            title: '¿Eliminar?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'inventario',
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                        'Content-Type': 'application/json'
                    },
                    data: JSON.stringify({
                        id_producto: id_producto,
                        id_almacen: id_almacen
                    }),
                    success: function () {
                        listarInventario();
                        Swal.fire('Eliminado', 'Registro eliminado correctamente.', 'success');
                    },
                    error: function (xhr) {
                        let msg = 'No se pudo eliminar el inventario.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire('Error', msg, 'error');
                    }
                });
            }
        });
    });
})();
</script>
