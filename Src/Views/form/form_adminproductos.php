<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<div class="container-fluid">
    <!--encabezado-->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-box-seam-fill text-primary me-2"></i><span data-i18n="administracion_de_productos">Administración de Productos</span>
            </h4>
            <small class="text-muted"><span data-i18n="gestion_catalogo_productos">Gestión del catálogo de productos de la ferretería</span></small>
        </div>
        <button type="button" class="btn btn-primary" onclick="abrirModalNuevoProducto()">
            <i class="bi bi-plus-lg me-1"></i><span data-i18n="nuevo_producto">  Nuevo Producto</span>
        </button>
    </div>
 
    <!--consultar-->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap gap-2 align-items-end">
            <div>
                <label class="form-label fw-bold mb-0"><span data-i18n="consultar_por_id">Consultar por ID</span></label>
                <input type="number" min="1" step="1" id="buscarIdProducto" class="form-control" style="width:150px" placeholder="Ej. 2">
            </div>
            <button class="btn btn-outline-primary" onclick="consultarProductoPorId()">
                <i class="bi bi-search me-1"></i> <span data-i18n="consultar_por_id">Consultar por ID</span>
            </button>
            <button class="btn btn-outline-secondary" onclick="cargarProductosTodos()">
                <i class="bi bi-list-ul me-1"></i> <span data-i18n="consultar_todo">Consultar Todos</span>
            </button>
        </div>
        <div id="resultadoProductoId" class="px-3 pb-3"></div>
    </div>
 
    <!--tabla-->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaProductosAdmin">
                    <thead class="table-light">
                        <tr>
                            <th >#</th>
                            <th>ID</th>
                            <th ><span data-i18n="codigo">Código</span></th>
                            <th><span data-i18n="nombre">Nombre</span></th>
                            <th><span data-i18n="categoria">Categoría</span></th>
                            <th><span data-i18n="unidad">Unidad</span></th>
                            <th><span data-i18n="precio_compra">Precio Compra</span></th>
                            <th><span data-i18n="precio_venta">Precio Venta</span></th>
                            <th class="text-center"><span data-i18n="acciones">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- carga dinamicamente -->
                    </tbody>
                </table>
            </div>
        </div>
    </div
</div>
 
<!--modal producto-->
<div class="modal fade" id="modalProductoAdmin" tabindex="-1" aria-labelledby="modalProductoAdminLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalProductoAdminLabel"><span data-i18n="nuevo_producto"> Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" onclick="cerrarModalProducto()"></button>
            </div>
            <form id="formProductoAdmin" onsubmit="guardarProductoAdmin(event)">
                <div class="modal-body">
                    <input type="hidden" id="id_producto" name="id_producto">
 
                    <div class="mb-3">
                        <label for="codigo" class="form-label fw-bold"><span data-i18n="codigo"> Código </span><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="codigo" name="codigo" required placeholder="Ej. 612720">
                    </div>
                    <div class="mb-3">
                        <label for="nombreProducto" class="form-label fw-bold"><span data-i18n="nombre"> Nombre </span><span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombreProducto" name="nombre" required placeholder="Ej. Martillo 16oz">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="precio_compra" class="form-label fw-bold"><span data-i18n="precio_compra"> Precio Compra </span><span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio_compra" name="precio_compra" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="precio_venta" class="form-label fw-bold"><span data-i18n="precio_compra"> Precio Venta </span><span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="precio_venta" name="precio_venta" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="fotografia" class="form-label fw-bold"><span data-i18n="fotografia_url_opcional"> Fotografía (URL, opcional)</span></label>
                        <input type="text" class="form-control" id="fotografia" name="fotografia" placeholder="https://...">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="id_categoria" class="form-label fw-bold"><span data-i18n="categoria"> Categoría</span><span class="text-danger">*</span></label>
                            <select class="form-select" id="id_categoria" name="id_categoria" required>
                                <option value=""><span data-i18n="cargando"> Cargando...</span></option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="id_unidad" class="form-label fw-bold"><span data-i18n="unidad_de_medida"> Unidad de Medida</span> <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_unidad" name="id_unidad" required>
                                <option value="1"><span data-i18n="unidad"> Unidad</span></option>
                                <option value="2"><span data-i18n="metro"> Metro</span></option>
                                <option value="3"><span data-i18n="libra"> Libra</span></option>
                                <option value="4"><span data-i18n="caja"> Caja</span></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModalProducto()"><span data-i18n="cancelar"> Cancelar</span></button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarProductoAdmin">
                        <i class="bi bi-save me-1"></i> <span data-i18n="guardar"> Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
 
<!--libreria de SweetAlert2 por si no esta en el layout global-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/idiomas.js"></script>
<script>

(function () {

    // TODO el código JavaScript del módulo
const tokenProductos = localStorage.getItem('token');
const ENDPOINT_PRODUCTOS = 'adminproductos';
const ENDPOINT_CATEGORIAS = 'category';


function abrirModalNuevoProducto() {
    document.getElementById('formProductoAdmin').reset();
    document.getElementById('id_producto').value = '';
    document.getElementById('modalProductoAdminLabel').innerText = 'Nuevo Producto';
 
    const modal = document.getElementById('modalProductoAdmin');
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
}
function cerrarModalProducto() {
    const modal = document.getElementById('modalProductoAdmin');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
 
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) backdrop.remove();
}
function cargarCategoriasSelect() {
    $.ajax({
        url: ENDPOINT_CATEGORIAS,
        type: 'GET',
        headers: { 'Authorization': 'Bearer ' + tokenProductos },
        dataType: 'json',
        success: function (res) {
            const cats = res.message || res.data || res;
            let opts = '';
            if (Array.isArray(cats)) {
                cats.forEach(c => {
                    opts += `<option value="${c.id_categoria}">${c.nombre}</option>`;
                });
            }
            $('#id_categoria').html(opts || '<option value="">Sin categorías</option>');
        },
        error: function () {
            $('#id_categoria').html('<option value="1">Herramientas Manuales</option><option value="2">Materiales de Construcción</option><option value="3">Ferretería General</option>');
        }
    });
}
function cargarProductosTodos() {
    $('#resultadoProductoId').html('');
    $('#buscarIdProducto').val('');
    $('#tablaProductosAdmin tbody').html('<tr><td colspan="9" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Cargando productos...</td></tr>');
    $.ajax({
        url: ENDPOINT_PRODUCTOS,
        type: 'GET',
        headers: { 'Authorization': 'Bearer ' + tokenProductos },
        dataType: 'json',
        success: function (res) {
            let list = res.message || res.data || res;
            renderTablaProductos(list);
        },
        error: function (xhr) {
            console.error('Error al cargar:', xhr.responseText);
            $('#tablaProductosAdmin tbody').html('<tr><td colspan="9" class="text-center py-4 text-danger">Error al consultar datos.</td></tr>');
        }
    });
}
function renderTablaProductos(data) {
    let html = '';
    if (!Array.isArray(data) || data.length === 0) {
        $('#tablaProductosAdmin tbody').html('<tr><td colspan="9" class="text-center py-4 text-muted">No hay productos registrados.</td></tr>');
        return;
    }
    data.forEach((p, index) => {
        const productoJson = JSON.stringify(p).replace(/"/g, '&quot;');
        html += `
            <tr>
                <td>${index + 1}</td>
                <td><strong>#${p.id_producto}</strong></td>
                <td>${p.codigo}</td>
                <td>${p.nombre}</td>
                <td>${p.categoria ?? p.id_categoria}</td>
                <td>${p.unidad ?? p.id_unidad}</td>
                <td>L. ${Number(p.precio_compra).toFixed(2)}</td>
                <td>L. ${Number(p.precio_venta).toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-secondary"
                        onclick='prepararEdicionProducto(${productoJson})'>
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger"
                        onclick="eliminarProductoAdmin(${p.id_producto}, '${(p.nombre || '').replace(/'/g, "\\'")}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    $('#tablaProductosAdmin tbody').html(html);
}
function prepararEdicionProducto(p) {
    document.getElementById('id_producto').value = p.id_producto;
    document.getElementById('codigo').value = p.codigo;
    document.getElementById('nombreProducto').value = p.nombre;
    document.getElementById('precio_compra').value = p.precio_compra;
    document.getElementById('precio_venta').value = p.precio_venta;
    document.getElementById('fotografia').value = p.fotografia || '';
    document.getElementById('id_categoria').value = p.id_categoria;
    document.getElementById('id_unidad').value = p.id_unidad;
    document.getElementById('modalProductoAdminLabel').innerText = 'Editar Producto';
 
    const modal = document.getElementById('modalProductoAdmin');
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
}
function guardarProductoAdmin(e) {
    e.preventDefault();
    const id = document.getElementById('id_producto').value;
    const esEdicion = id !== '';
    const metodo = esEdicion ? 'PUT' : 'POST';
    const payload = {
        codigo: document.getElementById('codigo').value.trim(),
        nombre: document.getElementById('nombreProducto').value.trim(),
        precio_compra: parseFloat(document.getElementById('precio_compra').value),
        precio_venta: parseFloat(document.getElementById('precio_venta').value),
        fotografia: document.getElementById('fotografia').value.trim(),
        id_categoria: parseInt(document.getElementById('id_categoria').value),
        id_unidad: parseInt(document.getElementById('id_unidad').value)
    };
    if (esEdicion) {
        payload.id_producto = parseInt(id);
    }
 
    $('#btnGuardarProductoAdmin').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');
    $.ajax({
        url: ENDPOINT_PRODUCTOS,
        type: metodo,
        headers: {
            'Authorization': 'Bearer ' + tokenProductos,
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(payload),
        dataType: 'json',
        success: function (res) {
            cerrarModalProducto();
            cargarProductosTodos();
            Swal.fire({
                icon: 'success',
                title: esEdicion ? 'Producto Actualizado' : 'Producto Creado',
                text: res.message || 'La operación se realizó con éxito.',
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'No se pudo guardar el producto.',
                confirmButtonColor: '#0d6efd'
            });
        },
        complete: function () {
            $('#btnGuardarProductoAdmin').prop('disabled', false).html('<i class="bi bi-save me-1"></i> Guardar');
        }
    });
}
function eliminarProductoAdmin(id, nombre) {
    Swal.fire({
        title: '¿Eliminar producto?',
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
                url: ENDPOINT_PRODUCTOS,
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + tokenProductos,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ id_producto: id }),
                dataType: 'json',
                success: function (res) {
                    cargarProductosTodos();
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: res.message || 'El producto fue eliminado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'No se pudo eliminar el producto.',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });
        }
    });
}
function consultarProductoPorId() {
    const id = document.getElementById('buscarIdProducto').value;
    const cont = document.getElementById('resultadoProductoId');
    if (!id || parseInt(id) < 1) {
        cont.innerHTML = '<div class="alert alert-warning mb-0">Ingresa un ID válido (1 o mayor).</div>';
        return;
    }
    $.ajax({
        url: ENDPOINT_PRODUCTOS + '?id=' + id,
        type: 'GET',
        headers: { 'Authorization': 'Bearer ' + tokenProductos },
        dataType: 'json',
        success: function (res) {
            const p = res.message;
            if (!p) {
                cont.innerHTML = `<div class="alert alert-warning mb-0">No se encontró ningún producto con ID ${id}.</div>`;
                return;
            }
            cont.innerHTML = `
                <div class="alert alert-info mb-0">
                    <strong>ID:</strong> ${p.id_producto} &nbsp;|&nbsp;
                    <strong>Código:</strong> ${p.codigo} &nbsp;|&nbsp;
                    <strong>Nombre:</strong> ${p.nombre} &nbsp;|&nbsp;
                    <strong>Categoría:</strong> ${p.categoria ?? p.id_categoria} &nbsp;|&nbsp;
                    <strong>Unidad:</strong> ${p.unidad ?? p.id_unidad} &nbsp;|&nbsp;
                    <strong>Compra:</strong> L. ${Number(p.precio_compra).toFixed(2)} &nbsp;|&nbsp;
                    <strong>Venta:</strong> L. ${Number(p.precio_venta).toFixed(2)}
                </div>`;
        },
        error: function () {
            cont.innerHTML = '<div class="alert alert-danger mb-0">Error al consultar el producto.</div>';
        }
    });
}
// Carga inicial
cargarCategoriasSelect();
cargarProductosTodos();


})();


</script>