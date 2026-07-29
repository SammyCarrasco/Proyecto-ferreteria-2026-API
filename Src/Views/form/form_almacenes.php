<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-building text-primary me-2"></i>
                <span data-i18n="alm_titulo">Gestión de Almacenes</span>
            </h4>
            <small class="text-muted" data-i18n="alm_subtitulo">Administración y registro de almacenes</small>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModalNueva()">
            <i class="bi bi-plus-lg me-1"></i> <span data-i18n="alm_btn_nuevo">Nuevo Almacén</span>
        </button>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaAlmacenes">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th data-i18n="tbl_id">ID</th>
                            <th data-i18n="tbl_nombre">Nombre</th>
                            <th data-i18n="tbl_ubicacion">Ubicación</th>
                            <th class="text-center" data-i18n="tbl_acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyAlmacenes">
                        <!-- Se carga dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Almacén -->
<div class="modal fade" id="modalAlmacen" tabindex="-1" aria-labelledby="modalAlmacenLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalAlmacenLabel" data-i18n="modal_almacen_titulo_nueva">Nuevo Almacén</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModal()"></button>
            </div>
            <form id="formAlmacen" onsubmit="guardarAlmacen(event)">
                <div class="modal-body">
                    <input type="hidden" id="id_almacen" name="id_almacen">

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">
                            <span data-i18n="lbl_nombre">Nombre</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej. Almacén Central" data-i18n-placeholder="ph_nombre">
                    </div>

                    <div class="mb-3">
                        <label for="ubicacion" class="form-label fw-bold">
                            <span data-i18n="lbl_ubicacion">Ubicación</span> <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required placeholder="Ej. Calle Principal #123" data-i18n-placeholder="ph_ubicacion">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()" data-i18n="btn_cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarAlmacen">
                        <i class="bi bi-save me-1"></i> <span data-i18n="btn_guardar">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/idiomas.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    const token = localStorage.getItem('token');
    const ENDPOINT = 'almacenes'; 

    function abrirModalNueva() {
        document.getElementById('formAlmacen').reset();
        document.getElementById('id_almacen').value = '';
        
        const lblModal = document.getElementById('modalAlmacenLabel');
        lblModal.setAttribute('data-i18n', 'modal_almacen_titulo_nueva');
        lblModal.innerText = 'Nuevo Almacén';
        
        if (typeof traducirPagina === 'function') traducirPagina();

        const modal = document.getElementById('modalAlmacen');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function cerrarModal() {
        const modal = document.getElementById('modalAlmacen');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
        document.body.classList.remove('modal-open');
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }

    function cargarAlmacenes() {
        $('#tbodyAlmacenes').html('<tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div><span data-i18n="msg_cargando">Cargando almacenes...</span></td></tr>');

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
                $('#tbodyAlmacenes').html('<tr><td colspan="5" class="text-center py-4 text-danger" data-i18n="msg_error_consultar">Error al consultar datos.</td></tr>');
                if (typeof traducirPagina === 'function') traducirPagina();
            }
        });
    }

    function renderTabla(data) {
        let html = '';
        if (!Array.isArray(data) || data.length === 0) {
            $('#tbodyAlmacenes').html('<tr><td colspan="5" class="text-center py-4 text-muted" data-i18n="msg_sin_almacenes">No hay almacenes registrados.</td></tr>');
            if (typeof traducirPagina === 'function') traducirPagina();
            return;
        }

        data.forEach((alm, index) => {
            const nombreLimpio = (alm.nombre || '').replace(/"/g, '&quot;');
            const ubicacionLimpia = (alm.ubicacion || '').replace(/"/g, '&quot;');

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>#${alm.id_almacen || '-'}</strong></td>
                    <td>${alm.nombre || '-'}</td>
                    <td>${alm.ubicacion || '-'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1 btn-editar" 
                            data-id="${alm.id_almacen}" 
                            data-nombre="${nombreLimpio}" 
                            data-ubicacion="${ubicacionLimpia}">
                            <i class="bi bi-pencil-square"></i> <span data-i18n="btn_editar">Editar</span>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                            data-id="${alm.id_almacen}" 
                            data-nombre="${nombreLimpio}">
                            <i class="bi bi-trash-fill"></i> <span data-i18n="btn_eliminar">Eliminar</span>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#tbodyAlmacenes').html(html);

        if (typeof traducirPagina === 'function') traducirPagina();
    }

    function prepararEdicion(id, nombre, ubicacion) {
        document.getElementById('id_almacen').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('ubicacion').value = ubicacion;
        
        const lblModal = document.getElementById('modalAlmacenLabel');
        lblModal.setAttribute('data-i18n', 'modal_almacen_titulo_editar');
        lblModal.innerText = 'Editar Almacén';
        
        if (typeof traducirPagina === 'function') traducirPagina();

        const modal = document.getElementById('modalAlmacen');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    function guardarAlmacen(e) {
        if (e) e.preventDefault();

        const id = document.getElementById('id_almacen').value;
        const esEdicion = id !== '';
        const metodo = esEdicion ? 'PUT' : 'POST';

        const payload = {
            nombre: document.getElementById('nombre').value.trim(),
            ubicacion: document.getElementById('ubicacion').value.trim()
        };

        if (esEdicion) {
            payload.id_almacen = id;
        }

        $('#btnGuardarAlmacen').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span><span data-i18n="btn_guardando">Guardando...</span>');
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
                cargarAlmacenes();

                Swal.fire({
                    icon: 'success',
                    title: esEdicion ? 'Almacén Actualizado' : 'Almacén Creado',
                    text: res.message || 'La operación se realizó con éxito.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo guardar el almacén.',
                    confirmButtonColor: '#0d6efd'
                });
            },
            complete: function () {
                $('#btnGuardarAlmacen').prop('disabled', false).html('<i class="bi bi-save me-1"></i> <span data-i18n="btn_guardar">Guardar</span>');
                if (typeof traducirPagina === 'function') traducirPagina();
            }
        });
    }

    function eliminarAlmacen(id, nombre) {
        Swal.fire({
            title: '¿Eliminar almacén?',
            text: `¿Estás seguro de que deseas eliminar "${nombre}"? Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: ENDPOINT + '/' + id,
                    type: 'DELETE',
                    headers: { 
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json' 
                    },
                    dataType: 'json',
                    success: function (res) {
                        cargarAlmacenes();
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: res.message || 'El almacén fue eliminado correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'No se pudo eliminar el almacén.',
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                });
            }
        });
    }

    // 1. Carga inicial
    cargarAlmacenes();

    // 2. Traducción inicial
    if (typeof traducirPagina === 'function') traducirPagina();

    // 3. Eventos de la UI
    $(document).off('click', '.btn-editar').on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const ubicacion = $(this).data('ubicacion');
        prepararEdicion(id, nombre, ubicacion);
    });

    $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        eliminarAlmacen(id, nombre);
    });

    $(document).off('submit', '#formAlmacen').on('submit', '#formAlmacen', function (e) {
        guardarAlmacen(e);
    });

    // 4. Lógica del botón de idioma
    let idiomaActual = localStorage.getItem('idioma') || 'es';
    $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

    $('#btn-idioma').on('click', function (e) {
        e.preventDefault();
        let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
        localStorage.setItem('idioma', nuevo);
        location.reload();
    });

    window.abrirModalNueva = abrirModalNueva;
    window.cerrarModal = cerrarModal;
})();
</script>
