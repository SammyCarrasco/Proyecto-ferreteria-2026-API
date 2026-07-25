<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Gestión de Empleados</h4>
            <small class="text-muted">Administración y registro de vendedores y personal del sistema</small>
        </div>
        <button class="btn btn-primary" id="btnNuevoEmpleado" data-bs-toggle="modal" data-bs-target="#modalEmpleado">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Empleado
        </button>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Identidad</th>
                            <th>Nombre Completo</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyEmpleados">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                Cargando empleados...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear / Editar Empleado -->
<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEmpleadoLabel"><i class="bi bi-person-plus me-2"></i>Registrar Empleado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEmpleado">
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" name="id_empleado">

                    <div class="mb-3">
                        <label for="identidad" class="form-label font-weight-bold">N° de Identidad</label>
                        <input type="text" class="form-control" id="identidad" name="identidad" placeholder="Ej. 0801199000000" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label font-weight-bold">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Carlos Mendoza" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label font-weight-bold">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@ferreteria.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol del Sistema</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="" disabled selected>Seleccione un rol...</option>
                            <option value="Normal">Normal (Vendedor)</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>

                    <div class="row" id="seccionClaves">
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" placeholder="••••••••">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmaclave" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmaclave" name="confirmaclave" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarEmpleado">
                        <i class="bi bi-save me-1"></i> Guardar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function listarEmpleados() {
            $.ajax({
                url: 'user', // Llama a Src/Routes/user.php
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                dataType: 'json',
                success: function (response) {
                    let html = '';
                    let lista = response.data || response;

                    if (Array.isArray(lista) && lista.length > 0) {
                        lista.forEach((emp, index) => {
                            let badgeRol = emp.rol === 'Administrador' 
                                ? '<span class="badge bg-danger-subtle text-danger">Administrador</span>' 
                                : '<span class="badge bg-info-subtle text-info">Normal</span>';

                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${emp.identidad || '-'}</td>
                                    <td class="fw-semibold">${emp.nombre}</td>
                                    <td>${emp.correo}</td>
                                    <td>${badgeRol}</td>
                                    <td>${emp.fecha_registro || '-'}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1 btn-editar" data-id="${emp.id_empleado}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${emp.id_empleado}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center py-4 text-muted">No se encontraron empleados registrados.</td></tr>';
                    }
                    $('#tbodyEmpleados').html(html);
                },
                error: function () {
                    $('#tbodyEmpleados').html('<tr><td colspan="7" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Error al consultar los empleados desde la API.</td></tr>');
                }
            });
        }

        // Cargar lista al inicio
        listarEmpleados();

        // Resetear modal al presionar 'Nuevo Empleado'
        $('#btnNuevoEmpleado').on('click', function () {
            $('#formEmpleado')[0].reset();
            $('#id_empleado').val('');
            $('#modalEmpleadoLabel').html('<i class="bi bi-person-plus me-2"></i>Registrar Empleado');
            $('#clave, #confirmaclave').prop('required', true);
        });

        // Guardar o Editar Empleado
        $('#formEmpleado').on('submit', function (e) {
            e.preventDefault();

            let clave = $('#clave').val();
            let confirmaclave = $('#confirmaclave').val();

            if (clave !== confirmaclave) {
                alert('Las contraseñas no coinciden. Por favor verifique.');
                return;
            }

            let id = $('#id_empleado').val();
            let metodo = id ? 'PUT' : 'POST';

            let payload = {
                id_empleado: id,
                identidad: $('#identidad').val().trim(),
                nombre: $('#nombre').val().trim(),
                correo: $('#correo').val().trim(),
                rol: $('#rol').val(),
                clave: clave,
                confirmaclave: confirmaclave
            };

            $.ajax({
                url: 'user',
                type: metodo,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(payload),
                dataType: 'json',
                success: function (res) {
                    $('#modalEmpleado').modal('hide');
                    listarEmpleados();
                },
                error: function (xhr) {
                    let msg = 'Error al procesar la solicitud.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
    });
</script>