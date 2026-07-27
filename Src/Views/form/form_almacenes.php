<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-building text-primary me-2"></i>Gestión de Almacenes</h4>
            <small class="text-muted">Administración y registro de almacenes</small>
        </div>
        <button class="btn btn-primary" id="btnNuevoAlmacen">
            <i class="bi bi-plus-circle me-1"></i> Nuevo Almacén
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyAlmacenes">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                Cargando almacenes...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalAlmacen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalAlmacenLabel"><i class="bi bi-plus-circle me-2"></i>Registrar Almacén</h5>
                <button type="button" class="btn-close btn-close-white" id="btnCerrarModalAlmacen"></button>
            </div>
            <form id="formAlmacen">
                <div class="modal-body">
                    <input type="hidden" id="id_almacen" name="id_almacen">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="btnCancelarAlmacen">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarAlmacen">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    let almacenesData = [];

    function abrirModalAlmacen() {
        $('#modalAlmacen').css('display', 'block').addClass('show');
        $('body').addClass('modal-open');
    }

    function cerrarModalAlmacen() {
        $('#modalAlmacen').css('display', 'none').removeClass('show');
        $('body').removeClass('modal-open');
    }

    // 1. LISTAR ALMACENES
    function listarAlmacenes() {
        $.ajax({
            url: 'almacenes',
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
            dataType: 'json',
            success: function (response) {
                almacenesData = response.data || response;
                let html = '';
                if (Array.isArray(almacenesData) && almacenesData.length > 0) {
                    almacenesData.forEach((alm, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${alm.nombre}</td>
                                <td>${alm.ubicacion}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1 btn-editar" data-id="${alm.id_almacen}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${alm.id_almacen}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });
                } else {
                    html = '<tr><td colspan="4" class="text-center py-4 text-muted">No se encontraron almacenes.</td></tr>';
                }
                $('#tbodyAlmacenes').html(html);
            },
            error: function () {
                $('#tbodyAlmacenes').html('<tr><td colspan="4" class="text-center text-danger">Error al consultar almacenes.</td></tr>');
            }
        });
    }
    listarAlmacenes();

    // 2. NUEVO
    $(document).on('click', '#btnNuevoAlmacen', function () {
        $('#formAlmacen')[0].reset();
        $('#id_almacen').val('');
        $('#modalAlmacenLabel').html('Registrar Almacén');
        abrirModalAlmacen();
    });

    // 3. CERRAR
    $(document).on('click', '#btnCancelarAlmacen, #btnCerrarModalAlmacen', cerrarModalAlmacen);

    // 4. EDITAR
    $(document).on('click', '.btn-editar', function () {
        let id = $(this).data('id');
        let alm = almacenesData.find(a => a.id_almacen == id);
        if (alm) {
            $('#id_almacen').val(alm.id_almacen);
            $('#nombre').val(alm.nombre);
            $('#ubicacion').val(alm.ubicacion);
            $('#modalAlmacenLabel').html('Editar Almacén');
            abrirModalAlmacen();
        }
    });

    // 5. GUARDAR
    $(document).on('submit', '#formAlmacen', function (e) {
        e.preventDefault();
        let id = $('#id_almacen').val();
        let payload = {
            nombre: $('#nombre').val().trim(),
            ubicacion: $('#ubicacion').val().trim()
        };
        let urlDestino = id ? 'almacenes/' + id : 'almacenes';
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
                cerrarModalAlmacen();
                listarAlmacenes();
                Swal.fire('Éxito', id ? 'Almacén actualizado.' : 'Almacén registrado.', 'success');
            },
            error: function () {
                Swal.fire('Error', 'No se pudo guardar el almacén.', 'error');
            }
        });
    });

    // 6. ELIMINAR
    $(document).on('click', '.btn-eliminar', function () {
        let id = $(this).data('id');
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
                    url: 'almacenes/' + id,
                    type: 'DELETE',
                    headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                    success: function () {
                        listarAlmacenes();
                        Swal.fire('Eliminado', 'Almacén eliminado.', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo eliminar el almacén.', 'error');
                    }
                });
            }
        });
    });
})();
</script>
