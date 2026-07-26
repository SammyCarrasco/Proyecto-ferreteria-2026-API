<div class="container-fluid">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Gestión de Clientes
            </h4>
            <small class="text-muted">
                Administración y registro de clientes
            </small>
        </div>

        <button type="button" class="btn btn-primary" onclick="abrirModalNuevo()">
            <i class="bi bi-plus-lg me-1"></i>
            Nuevo Cliente
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
                            <th>ID</th>
                            <th>RTN</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Fecha Registro</th>
                            <th class="text-center">
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

<div class="modal fade" 
     id="modalCliente" 
     tabindex="-1"
     aria-labelledby="modalClienteLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title fw-bold" id="modalClienteLabel">
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
                            RTN
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
                            Nombre
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nombre"
                               required
                               placeholder="Nombre del cliente">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
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
                            onclick="cerrarModal()">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="btn btn-primary"
                            id="btnGuardarCliente">

                        <i class="bi bi-save me-1"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const token = localStorage.getItem('token');
const ENDPOINT = 'clientes';
//const ENDPOINT = 'http://localhost:8012/Proyecto-ferreteria-2026-API/public/clientes';
// ABRIR MODAL NUEVO

function abrirModalNuevo(){
    document.getElementById('formCliente').reset();
    document.getElementById('id_cliente').value = '';
    document.getElementById('modalClienteLabel').innerText = 
    'Nuevo Cliente';
    const modal = document.getElementById('modalCliente');
    modal.classList.add('show');
    modal.style.display='block';
    document.body.classList.add('modal-open');

}
// CERRAR MODAL
function cerrarModal(){
    const modal = document.getElementById('modalCliente');
    modal.classList.remove('show');
    modal.style.display='none';
    document.body.classList.remove('modal-open');
    const backdrop=document.querySelector('.modal-backdrop');
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
        type:'GET',
        headers:{
            'Authorization':'Bearer '+token
        },
        dataType:'json',
        success:function(res){
            let lista = res.data || res;
            renderTabla(lista);
        },
        error:function(xhr){
            console.error(xhr.responseText);
            $('#tablaClientes tbody').html(`
                <tr>
                    <td colspan="7" 
                    class="text-center py-4 text-danger">
                        Error al consultar clientes.
                    </td>
                </tr>
            `);
        }
    });
}

// MOSTRAR TABLA
function renderTabla(data){
    let html='';
    if(!Array.isArray(data) || data.length===0){
        $('#tablaClientes tbody').html(`
            <tr>
                <td colspan="7"
                class="text-center py-4 text-muted">
                    No hay clientes registrados.
                </td>
            </tr>
        `);
        return;
    }
    data.forEach((cliente,index)=>{
        const nombre =
        (cliente.nombre || '').replace(/'/g,"\\'");
        html+=`
        <tr>
            <td>${index+1}</td>
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
function prepararEdicion(id,rtn,nombre,telefono){
    document.getElementById('id_cliente').value=id;
    document.getElementById('rtn').value=rtn;
    document.getElementById('nombre').value=nombre;
    document.getElementById('telefono').value=telefono;
    document.getElementById('modalClienteLabel').innerText =
    'Editar Cliente';
    const modal=document.getElementById('modalCliente');
    modal.classList.add('show');
    modal.style.display='block';
    document.body.classList.add('modal-open');
}
// GUARDAR / ACTUALIZAR
function guardarCliente(e){
    e.preventDefault();
    const id =
    document.getElementById('id_cliente').value;
    const editar = id !== '';
    const metodo = editar ? 'PUT':'POST';
    const datos={
        rtn:
        document.getElementById('rtn').value.trim(),
        nombre:
        document.getElementById('nombre').value.trim(),
        telefono:
        document.getElementById('telefono').value.trim()
    };
    $('#btnGuardarCliente')
    .prop('disabled',true)
    .html(`
    <span class="spinner-border spinner-border-sm me-1"></span>
    Guardando...
    `);
    let url = editar ? ENDPOINT+'/'+id : ENDPOINT;
    $.ajax({
        url:url,
        type:metodo,
        headers:{
            'Authorization':'Bearer '+token,
            'Content-Type':'application/json'
        },
        data:JSON.stringify(datos),
        dataType:'json',
        success:function(res){
            cerrarModal();
            cargarClientes();
            Swal.fire({
                icon:'success',
                title:
                editar?
                'Cliente actualizado':
                'Cliente registrado',
                text:
                res.message ||
                'Operación realizada correctamente',
                timer:2000,
                showConfirmButton:false
            });
        },
        error:function(xhr){
            Swal.fire({
                icon:'error',
                title:'Error',
                text:
                xhr.responseJSON?.message ||
                'No se pudo guardar el cliente'
            });
        },
        complete:function(){
            $('#btnGuardarCliente')
            .prop('disabled',false)
            .html(`
            <i class="bi bi-save me-1"></i>
            Guardar

            `);

        }
    });
}

// ELIMINAR CLIENTE

function eliminarCliente(id,nombre){

    Swal.fire({
        title:'¿Eliminar cliente?',
        text:
        `¿Deseas eliminar "${nombre}"?`,
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#dc3545',
        cancelButtonColor:'#6c757d',
        confirmButtonText:'Sí, eliminar',
        cancelButtonText:'Cancelar'

    }).then((result)=>{

        if(result.isConfirmed){
            $.ajax({
                url:ENDPOINT+'/'+id,
                type:'DELETE',
                headers:{

                    'Authorization':'Bearer '+token
                },
                dataType:'json',
                success:function(res){
                    cargarClientes();
                    Swal.fire({
                        icon:'success',
                        title:'Cliente eliminado',
                        text:
                        res.message ||
                        'El cliente fue eliminado correctamente',
                        timer:2000,
                        showConfirmButton:false
                    });
                },
                error:function(xhr){
                    Swal.fire({
                        icon:'error',
                        title:'Error',
                        text:
                        xhr.responseJSON?.message ||
                        'No se pudo eliminar el cliente'
                    });
                }
            });
        }
    });
}

// CARGA INICIAL

cargarClientes();
</script>