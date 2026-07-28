<div class="container-fluid">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-people-fill text-primary me-2"></i>
                <span data-i18n="gestion_de_clientes">Gestión de Clientes</span>
            </h4>
            <small class="text-muted" data-i18n="administracion_y_registro_clientes">
                Administración y registro de clientes
            </small>
        </div>

        <button type="button" class="btn btn-primary" onclick="abrirModalNuevo()">
            <i class="bi bi-plus-lg me-1"></i>
            <span data-i18n="nuevo_cliente">Nuevo Cliente</span>
        </button>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaClientes">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th data-i18n="id">ID</th>
                            <th data-i18n="rtn">RTN</th>
                            <th data-i18n="nombre">Nombre</th>
                            <th data-i18n="telefono">Teléfono</th>
                            <th data-i18n="fecha_registro">Fecha Registro</th>
                            <th class="text-center" data-i18n="acciones">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" 
     id="modalCliente" 
     tabindex="-1"
     aria-labelledby="modalClienteLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalClienteLabel" data-i18n="nuevo_cliente">
                    Nuevo Cliente
                </h5>
                <button type="button"
                        class="btn-close btn-close-white"
                        onclick="cerrarModal()">
                </button>
            </div>

            <form id="formCliente" onsubmit="guardarCliente(event)">
                <div class="modal-body">
                    <input type="hidden" id="id_cliente">

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <span data-i18n="rtn">RTN</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="rtn"
                               required
                               placeholder="Ej. 08011999123456">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <span data-i18n="nombre">Nombre</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nombre"
                               required
                               placeholder="Nombre del cliente">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" data-i18n="telefono">
                            Teléfono
                        </label>
                        <input type="text"
                               class="form-control"
                               id="telefono"
                               placeholder="Ej. 99887766">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button"
                            class="btn btn-secondary"
                            onclick="cerrarModal()"
                            data-i18n="cancelar">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn btn-primary"
                            id="btnGuardarCliente">
                        <i class="bi bi-save me-1"></i>
                        <span data-i18n="guardar">Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/idiomas.js"></script>
<script>
console.log("CLIENTES CARGADO");

              /*  let idiomaActual = localStorage.getItem('idioma') || 'es';
                $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

                $('#btn-idioma').on('click', function (e) {
                    e.preventDefault();
                    let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
                    localStorage.setItem('idioma', nuevo);
                    location.reload();
                });
                */

(function(){
   // console.log('IIFE de clientes ejecutándose, hora:', new Date().toISOString());
    const token = localStorage.getItem('token');
    
    // Depuración para ver qué rol tiene la sesión actual en la consola (F12)
    /*const rolUsuario = localStorage.getItem('rol') || localStorage.getItem('tipo_usuario') || localStorage.getItem('id_rol') || localStorage.getItem('tipo');
    console.log("Rol detectado en Clientes:", rolUsuario);

    // Validación de rol de seguridad para administradores
    if (rolUsuario !== '1' && rolUsuario !== 'Administrador' && rolUsuario !== 'admin') {
        document.querySelector('.container-fluid').innerHTML = `
            <div class="alert alert-warning shadow-sm border-0 mt-3" role="alert">
                <i class="bi bi-lock-fill me-2"></i>
                Debes ser <strong>administrador</strong> para acceder a este módulo.
            </div>
        `;
        return; // Detiene la ejecución para bloquear la vista
    }*/

    const ENDPOINT = 'clientes';

    // ABRIR MODAL NUEVO
    function abrirModalNuevo(){
        document.getElementById('formCliente').reset();
        document.getElementById('id_cliente').value = '';
        document.getElementById('modalClienteLabel').innerText = 'Nuevo Cliente';
        const modal = document.getElementById('modalCliente');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    // CERRAR MODAL
    function cerrarModal(){
        const modal = document.getElementById('modalCliente');
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.classList.remove('modal-open');
        const backdrop = document.querySelector('.modal-backdrop');
        if(backdrop){
            backdrop.remove();
        }
    }

    // CARGAR CLIENTES
    function cargarClientes(){
        $('#tablaClientes tbody').html(`
            <tr>
                <td colspan="7" class="text-center py-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Cargando clientes...
                </td>
            </tr>
        `);
        $.ajax({
            url: ENDPOINT,
            type: 'GET',
            cache: false,
            headers: {
                'Authorization': 'Bearer ' + token
            },
            dataType: 'json',
            success: function(res){
                let lista = res.data || res;
                renderTabla(lista);
            },
            error: function(xhr){
                console.error(xhr.responseText);
                $('#tablaClientes tbody').html(`
                    <tr>
                        <td colspan="7" class="text-center py-4 text-danger">
                            Error al consultar clientes.
                        </td>
                    </tr>
                `);
            }
        });
    }

    // MOSTRAR TABLA
    function renderTabla(data){
        let html = '';
        if(!Array.isArray(data) || data.length === 0){
            $('#tablaClientes tbody').html(`
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        No hay clientes registrados.
                    </td>
                </tr>
            `);
            return;
        }
        data.forEach((cliente, index) => {
            const nombre = (cliente.nombre || '').replace(/'/g, "\\'");
            html += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <strong>
                        #${cliente.id_cliente || '-'}
                    </strong>
                </td>
                <td>
                    ${cliente.rtn || '-'}
                </td>
                <td>
                    ${cliente.nombre || '-'}
                </td>
                <td>
                    ${cliente.telefono || '-'}
                </td>
                <td>
                    ${cliente.fecha_registro || '-'}
                </td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-primary me-1"
                    onclick="prepararEdicion(
                        ${cliente.id_cliente},
                        '${cliente.rtn}',
                        '${nombre}',
                        '${cliente.telefono || ''}'
                    )">
                        <i class="bi bi-pencil-square"></i>
                        Editar
                    </button>
                    <button class="btn btn-sm btn-outline-danger"
                    onclick="eliminarCliente(
                        ${cliente.id_cliente},
                        '${nombre}'
                    )">
                        <i class="bi bi-trash-fill"></i>
                        Eliminar
                    </button>
                </td>
            </tr>
            `;
        });
        $('#tablaClientes tbody').html(html);
    }

    // PREPARAR EDICIÓN
    function prepararEdicion(id, rtn, nombre, telefono){
        document.getElementById('id_cliente').value = id;
        document.getElementById('rtn').value = rtn;
        document.getElementById('nombre').value = nombre;
        document.getElementById('telefono').value = telefono;
        document.getElementById('modalClienteLabel').innerText = 'Editar Cliente';
        const modal = document.getElementById('modalCliente');
        modal.classList.add('show');
        modal.style.display = 'block';
        document.body.classList.add('modal-open');
    }

    // GUARDAR / ACTUALIZAR
    function guardarCliente(e){
        e.preventDefault();
        const id = document.getElementById('id_cliente').value;
        const editar = id !== '';
        const metodo = editar ? 'PUT' : 'POST';
        const datos = {
            rtn: document.getElementById('rtn').value.trim(),
            nombre: document.getElementById('nombre').value.trim(),
            telefono: document.getElementById('telefono').value.trim()
        };
        $('#btnGuardarCliente')
        .prop('disabled', true)
        .html(`
            <span class="spinner-border spinner-border-sm me-1"></span>
            Guardando...
        `);
        let url = editar ? ENDPOINT + '/' + id : ENDPOINT;
        $.ajax({
            url: url,
            type: metodo,
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json'
            },
            data: JSON.stringify(datos),
            dataType: 'json',
            success: function(res){
                cerrarModal();
                cargarClientes();
                Swal.fire({
                    icon: 'success',
                    title: editar ? 'Cliente actualizado' : 'Cliente registrado',
                    text: res.message || 'Operación realizada correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'No se pudo guardar el cliente'
                });
            },
            complete: function(){
                $('#btnGuardarCliente')
                .prop('disabled', false)
                .html(`
                    <i class="bi bi-save me-1"></i>
                    Guardar
                `);
            }
        });
    }

    // ELIMINAR CLIENTE
    function eliminarCliente(id, nombre){
        Swal.fire({
            title: '¿Eliminar cliente?',
            text: `¿Deseas eliminar "${nombre}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: ENDPOINT + '/' + id,
                    type: 'DELETE',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    dataType: 'json',
                    success: function(res){
                        cargarClientes();
                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente eliminado',
                            text: res.message || 'El cliente fue eliminado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'No se pudo eliminar el cliente'
                        });
                    }
                });
            }
        });
    }

    // CARGA INICIAL
    function iniciarClientes(){
        console.log("Inicializando módulo clientes...");
        cargarClientes();
    }

    iniciarClientes();

    // Hacer visibles las funciones usadas en botones HTML
    window.abrirModalNuevo = abrirModalNuevo;
    window.cerrarModal = cerrarModal;
    window.guardarCliente = guardarCliente;
    window.prepararEdicion = prepararEdicion;
    window.eliminarCliente = eliminarCliente;

})();
</script>