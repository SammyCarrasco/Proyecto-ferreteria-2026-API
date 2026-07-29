<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-box-seam text-primary me-2"></i>
                <span data-i18n="inv_titulo">Gestión de Inventario</span>
            </h4>
            <small class="text-muted" data-i18n="inv_subtitulo">Control de productos en almacenes</small>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModalNueva()">
            <i class="bi bi-plus-lg me-1"></i> <span data-i18n="inv_btn_nuevo">Asociar Producto</span>
        </button>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaInventario">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th data-i18n="tbl_producto">Producto</th>
                            <th data-i18n="tbl_almacen">Almacén</th>
                            <th data-i18n="tbl_stock_disp">Stock Disponible</th>
                            <th data-i18n="tbl_stock_res">Stock Reservado</th>
                            <th class="text-center" data-i18n="tbl_acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Se carga dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Inventario -->
<div class="modal fade" id="modalInventario" tabindex="-1" aria-labelledby="modalInventarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalInventarioLabel" data-i18n="modal_inv_titulo_nuevo">Asociar Producto</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModal()"></button>
            </div>
            <form id="formInventario" onsubmit="guardarInventario(event)">
                <div class="modal-body">
                    <input type="hidden" id="id_inventario" name="id_inventario">

                    <div class="mb-3">
                        <label for="id_producto" class="form-label fw-bold">
                            <span data-i18n="lbl_producto">ID Producto</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="id_producto" name="id_producto" required placeholder="Ej. 101" data-i18n-placeholder="ph_producto">
                    </div>

                    <div class="mb-3">
                        <label for="id_almacen" class="form-label fw-bold">
                            <span data-i18n="lbl_almacen">ID Almacén</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="id_almacen" name="id_almacen" required placeholder="Ej. 5" data-i18n-placeholder="ph_almacen">
                    </div>

                    <div class="mb-3">
                        <label for="stock_disponible" class="form-label fw-bold">
                            <span data-i18n="lbl_stock_disp">Stock Disponible</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" required placeholder="Ej. 50" data-i18n-placeholder="ph_stock_disp">
                    </div>

                    <div class="mb-3">
                        <label for="stock_reservado" class="form-label fw-bold">
                            <span data-i18n="lbl_stock_res">Stock Reservado</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control" id="stock_reservado" name="stock_reservado" required placeholder="Ej. 10" data-i18n-placeholder="ph_stock_res">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()" data-i18n="btn_cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarInventario">
                        <i class="bi bi-save me-1"></i> <span data-i18n="btn_guardar">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/idiomas.js"></script>
<script>
(function () {
    const token = localStorage.getItem('token');
    const ENDPOINT = 'inventario'; 

    // Abrir modal para nuevo registro
    function abrirModalNueva() {
        document.getElementById('formInventario').reset();
        document.getElementById('id_inventario').value = '';
        
        const lblModal = document.getElementById('modalInventarioLabel');
        lblModal.setAttribute('data-i18n', 'modal_inv_titulo_nuevo');
        lblModal.innerText = 'Asociar Producto';
        
        if (typeof traducirPagina === 'function') traducirPagina();

        const modal = document.getElementById('modalInventario');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    // Cerrar modal
    function cerrarModal() {
        const modal = document.getElementById('modalInventario');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
        document.body.classList.remove('modal-open');
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }

    // Cargar inventario desde API
    function cargarInventario() {
        $('#tablaInventario tbody').html(
            '<tr><td colspan="6" class="text-center py-4 text-muted">' +
            '<div class="spinner-border spinner-border-sm text-primary me-2"></div>' +
            '<span data-i18n="msg_cargando">Cargando inventario...</span></td></tr>'
        );

        if (typeof traducirPagina === 'function') traducirPagina();

        $.ajax({
            url: ENDPOINT,
            type: 'GET',
            headers: { 'Authorization': 'Bearer ' + token },
            dataType: 'json',
            success: function (res) {
                let list = [];
                if (Array.isArray(res.message)) {
                    list = res.message;
                } else if (Array.isArray(res.data)) {
                    list = res.data;
                } else if (Array.isArray(res)) {
                    list = res;
                }
                renderTabla(list);
            },
            error: function (xhr) {
                console.error('Error al cargar:', xhr.responseText);
                $('#tablaInventario tbody').html(
                    '<tr><td colspan="6" class="text-center py-4 text-danger" data-i18n="msg_error_consultar">Error al consultar inventario.</td></tr>'
                );
                if (typeof traducirPagina === 'function') traducirPagina();
            }
        });
    }

        // Renderizar tabla con traducción
    function renderTabla(data) {
        let html = '';
        if (!Array.isArray(data) || data.length === 0) {
            $('#tablaInventario tbody').html(
                '<tr><td colspan="6" class="text-center py-4 text-muted" data-i18n="msg_sin_inventario">No hay productos en inventario.</td></tr>'
            );
            if (typeof traducirPagina === 'function') traducirPagina();
            return;
        }

        data.forEach((inv, index) => {
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${inv.producto || inv.id_producto}</td>
                    <td>${inv.almacen || inv.id_almacen}</td>
                    <td>${inv.stock_disponible}</td>
                    <td>${inv.stock_reservado}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1 btn-editar" 
                            data-id="${inv.id_inventario}" 
                            data-id_producto="${inv.id_producto}" 
                            data-id_almacen="${inv.id_almacen}" 
                            data-stock_disp="${inv.stock_disponible}" 
                            data-stock_res="${inv.stock_reservado}">
                            <i class="bi bi-pencil-square"></i> <span data-i18n="btn_editar">Editar</span>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                            data-id_producto="${inv.id_producto}" 
                            data-id_almacen="${inv.id_almacen}">
                            <i class="bi bi-trash-fill"></i> <span data-i18n="btn_eliminar">Eliminar</span>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#tablaInventario tbody').html(html);

        if (typeof traducirPagina === 'function') traducirPagina();
    }

    // Preparar edición de un registro
    function prepararEdicion(inv) {
        document.getElementById('id_inventario').value = inv.id;
        document.getElementById('id_producto').value = inv.id_producto;
        document.getElementById('id_almacen').value = inv.id_almacen;
        document.getElementById('stock_disponible').value = inv.stock_disp;
        document.getElementById('stock_reservado').value = inv.stock_res;

        const lblModal = document.getElementById('modalInventarioLabel');
        lblModal.setAttribute('data-i18n', 'modal_inv_titulo_editar');
        lblModal.innerText = 'Editar Inventario';

        if (typeof traducirPagina === 'function') traducirPagina();

        const modal = document.getElementById('modalInventario');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

        // Guardar inventario (crear/editar)
    function guardarInventario(e) {
        if (e) e.preventDefault();

        const id = document.getElementById('id_inventario').value;
        const esEdicion = id !== '';
        const metodo = esEdicion ? 'PUT' : 'POST';

        const payload = {
            id_producto: document.getElementById('id_producto').value.trim(),
            id_almacen: document.getElementById('id_almacen').value.trim(),
            stock_disponible: document.getElementById('stock_disponible').value.trim(),
            stock_reservado: document.getElementById('stock_reservado').value.trim()
        };

        if (esEdicion) {
            payload.id_inventario = id;
        }

        $('#btnGuardarInventario').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span>' +
            '<span data-i18n="btn_guardando">Guardando...</span>'
        );
        if (typeof traducirPagina === 'function') traducirPagina();

        $.ajax({
            url: ENDPOINT + (esEdicion ? '/' + id : ''),
            type: metodo,
            headers: { 
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json' 
            },
            data: JSON.stringify(payload),
            dataType: 'json',
            success: function (res) {
                cerrarModal();
                cargarInventario();

                Swal.fire({
                    icon: 'success',
                    title: esEdicion ? 'Inventario Actualizado' : 'Producto Asociado',
                    text: res.message || 'La operación se realizó con éxito.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo guardar el inventario.',
                    confirmButtonColor: '#0d6efd'
                });
            },
            complete: function () {
                $('#btnGuardarInventario').prop('disabled', false).html(
                    '<i class="bi bi-save me-1"></i> <span data-i18n="btn_guardar">Guardar</span>'
                );
                if (typeof traducirPagina === 'function') traducirPagina();
            }
        });
    }

    // Eliminar inventario
    function eliminarInventario(id_producto, id_almacen) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ENDPOINT,
                    type: 'DELETE',
                    headers: { 
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json' 
                    },
                    data: JSON.stringify({ id_producto, id_almacen }),
                    dataType: 'json',
                    success: function (res) {
                        cargarInventario();
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: res.message || 'El registro fue eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'No se pudo eliminar el registro.',
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                });
            }
        });
    }

        // 1. Carga inicial
    cargarInventario();

    // 2. Traducción inicial
    if (typeof traducirPagina === 'function') traducirPagina();

    // 3. Eventos de la UI
    $(document).off('click', '.btn-editar').on('click', '.btn-editar', function () {
        prepararEdicion({
            id: $(this).data('id'),
            id_producto: $(this).data('id_producto'),
            id_almacen: $(this).data('id_almacen'),
            stock_disp: $(this).data('stock_disp'),
            stock_res: $(this).data('stock_res')
        });
    });

    $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function () {
        const id_producto = $(this).data('id_producto');
        const id_almacen = $(this).data('id_almacen');
        eliminarInventario(id_producto, id_almacen);
    });

    $(document).off('submit', '#formInventario').on('submit', '#formInventario', function (e) {
        guardarInventario(e);
    });

    // 4. Botón de idioma
    let idiomaActual = localStorage.getItem('idioma') || 'es';
    $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

    $('#btn-idioma').on('click', function (e) {
        e.preventDefault();
        let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
        localStorage.setItem('idioma', nuevo);
        location.reload();
    });

    // Exponer funciones globales
    window.abrirModalNueva = abrirModalNueva;
    window.cerrarModal = cerrarModal;
})();
</script>
