<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-tags-fill text-primary me-2"></i><span data-i18n="cat_titulo_modulo">Gestión de Categorías</span>
            </h4>
            <small class="text-muted" data-i18n="cat_subtitulo_modulo">Administración y registro de categorías de productos</small>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModalNueva()">
            <i class="bi bi-plus-lg me-1"></i> <span data-i18n="cat_btn_nueva">Nueva Categoría</span>
        </button>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaCategorias">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th data-i18n="cat_th_id">ID</th>
                            <th data-i18n="cat_th_nombre">Nombre</th>
                            <th data-i18n="cat_th_descripcion">Descripción</th>
                            <th class="text-center" data-i18n="cat_th_acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Carga dinámicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalCategoriaLabel" data-i18n="cat_modal_titulo_nueva">Nueva Categoría</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModal()"></button>
            </div>
            <form id="formCategoria" onsubmit="guardarCategoria(event)">
                <div class="modal-body">
                    <input type="hidden" id="id_categoria" name="id_categoria">

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold"><span data-i18n="cat_lbl_nombre">Nombre</span> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ej. Herramientas" data-i18n-placeholder="cat_ph_nombre">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label fw-bold"><span data-i18n="cat_lbl_descripcion">Descripción</span> <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required placeholder="Descripción..." data-i18n-placeholder="cat_ph_descripcion"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()" data-i18n="cat_btn_cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoria">
                        <i class="bi bi-save me-1"></i> <span data-i18n="cat_btn_guardar">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/idiomas.js"></script>

<script>
(function () {
    const token = localStorage.getItem('token');
    const ENDPOINT = 'category'; 

    // Función segura para aplicar la traducción
    function aplicarTraduccion() {
        if (typeof traducirPagina === 'function') {
            traducirPagina();
        }
    }

    function abrirModalNueva() {
        document.getElementById('formCategoria').reset();
        document.getElementById('id_categoria').value = '';
        
        const label = document.getElementById('modalCategoriaLabel');
        label.setAttribute('data-i18n', 'cat_modal_titulo_nueva');

        const modal = document.getElementById('modalCategoria');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
        
        aplicarTraduccion();
    }

    function cerrarModal() {
        const modal = document.getElementById('modalCategoria');
        if (modal) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
        document.body.classList.remove('modal-open');
        
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.remove();
    }

    function cargarCategorias() {
        $('#tablaCategorias tbody').html('<tr><td colspan="5" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div><span data-i18n="cat_msg_cargando">Cargando categorías...</span></td></tr>');
        aplicarTraduccion();

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
                $('#tablaCategorias tbody').html('<tr><td colspan="5" class="text-center py-4 text-danger" data-i18n="cat_msg_error">Error al consultar datos.</td></tr>');
                aplicarTraduccion();
            }
        });
    }

    function renderTabla(data) {
        let html = '';
        if (!Array.isArray(data) || data.length === 0) {
            $('#tablaCategorias tbody').html('<tr><td colspan="5" class="text-center py-4 text-muted" data-i18n="cat_msg_sin_registros">No hay categorías registradas.</td></tr>');
            aplicarTraduccion();
            return;
        }

        data.forEach((cat, index) => {
            const nombreLimpio = (cat.nombre || '').replace(/"/g, '&quot;');
            const descLimpia = (cat.descripcion || '').replace(/"/g, '&quot;');

            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>#${cat.id_categoria || '-'}</strong></td>
                    <td>${cat.nombre || '-'}</td>
                    <td>${cat.descripcion || '-'}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary me-1 btn-editar" 
                            data-id="${cat.id_categoria}" 
                            data-nombre="${nombreLimpio}" 
                            data-descripcion="${descLimpia}">
                            <i class="bi bi-pencil-square"></i> <span data-i18n="cat_btn_editar">Editar</span>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                            data-id="${cat.id_categoria}" 
                            data-nombre="${nombreLimpio}">
                            <i class="bi bi-trash-fill"></i> <span data-i18n="cat_btn_eliminar">Eliminar</span>
                        </button>
                    </td>
                </tr>
            `;
        });

        $('#tablaCategorias tbody').html(html);
        aplicarTraduccion();
    }

    function prepararEdicion(id, nombre, descripcion) {
        document.getElementById('id_categoria').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('descripcion').value = descripcion;
        
        const label = document.getElementById('modalCategoriaLabel');
        label.setAttribute('data-i18n', 'cat_modal_titulo_editar');

        const modal = document.getElementById('modalCategoria');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
        
        aplicarTraduccion();
    }

    function guardarCategoria(e) {
        if (e) e.preventDefault();

        const id = document.getElementById('id_categoria').value;
        const esEdicion = id !== '';
        const metodo = esEdicion ? 'PUT' : 'POST';

        const payload = {
            nombre: document.getElementById('nombre').value.trim(),
            descripcion: document.getElementById('descripcion').value.trim()
        };

        if (esEdicion) {
            payload.id_categoria = id;
        }

        $('#btnGuardarCategoria').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

        $.ajax({
            url: ENDPOINT,
            type: metodo,
            headers: { 
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json' 
            },
            data: JSON.stringify(payload),
            dataType: 'json',
            success: function (res) {
                cerrarModal();
                cargarCategorias();

                Swal.fire({
                    icon: 'success',
                    title: esEdicion ? 'Categoría Actualizada' : 'Categoría Creada',
                    text: res.message || 'La operación se realizó con éxito.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo guardar la categoría.',
                    confirmButtonColor: '#0d6efd'
                });
            },
            complete: function () {
                $('#btnGuardarCategoria').prop('disabled', false).html('<i class="bi bi-save me-1"></i> <span data-i18n="cat_btn_guardar">Guardar</span>');
                aplicarTraduccion();
            }
        });
    }

    function eliminarCategoria(id, nombre) {
        Swal.fire({
            title: '¿Eliminar categoría?',
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
                    url: ENDPOINT,
                    type: 'DELETE',
                    headers: { 
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json' 
                    },
                    data: JSON.stringify({ id_categoria: id }),
                    dataType: 'json',
                    success: function (res) {
                        cargarCategorias();
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminada',
                            text: res.message || 'La categoría fue eliminada correctamente.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'No se pudo eliminar la categoría.',
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                });
            }
        });
    }

    // 1. Carga inicial
    cargarCategorias();
    
    // Ejecutar traducción inmediatamente al cargar la vista
    aplicarTraduccion();

    // 2. Monitoreo de cambio de idioma en tiempo real
    // Detecta clics en el botón/menú del navbar superior de idioma
    $(document).on('click', '#btn-idioma, .dropdown-item-idioma, [onclick*="idioma"]', function() {
        setTimeout(aplicarTraduccion, 100);
    });

    // Verificación constante por si la variable en localStorage cambia en otra pestaña/menú
    let ultimoIdioma = localStorage.getItem('idioma');
    setInterval(function() {
        const idiomaActual = localStorage.getItem('idioma');
        if (idiomaActual !== ultimoIdioma) {
            ultimoIdioma = idiomaActual;
            aplicarTraduccion();
        }
    }, 500);

    // 3. Escuchadores de botones de la tabla y formulario
    $(document).off('click', '.btn-editar').on('click', '.btn-editar', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const desc = $(this).data('descripcion');
        prepararEdicion(id, nombre, desc);
    });

    $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function () {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        eliminarCategoria(id, nombre);
    });

    $(document).off('submit', '#formCategoria').on('submit', '#formCategoria', function (e) {
        guardarCategoria(e);
    });

    window.abrirModalNueva = abrirModalNueva;
    window.cerrarModal = cerrarModal;
})();
</script>