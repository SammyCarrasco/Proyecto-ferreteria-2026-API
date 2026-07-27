<!-- Cargar SweetAlert2 para alertas elegantes -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">
                <i class="bi bi-people-fill text-primary me-2"></i><span data-i18n="emp_titulo_modulo">Gestión de Empleados</span>
            </h4>
            <small class="text-muted" data-i18n="emp_subtitulo_modulo">Administración y registro de vendedores y personal del sistema</small>
        </div>
        <button class="btn btn-primary" id="btnNuevoEmpleado">
            <i class="bi bi-person-plus-fill me-1"></i> <span data-i18n="emp_btn_nuevo">Nuevo Empleado</span>
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
                            <th data-i18n="emp_th_identidad">Identidad</th>
                            <th data-i18n="emp_th_nombre">Nombre Completo</th>
                            <th data-i18n="emp_th_correo">Correo Electrónico</th>
                            <th data-i18n="emp_th_rol">Rol</th>
                            <th data-i18n="emp_th_fecha">Fecha Registro</th>
                            <th class="text-end" data-i18n="emp_th_acciones">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyEmpleados">
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                <span data-i18n="emp_msg_cargando">Cargando empleados...</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear / Editar Empleado -->
<div class="modal fade" id="modalEmpleado" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalEmpleadoLabel">
                    <i class="bi bi-person-plus me-2"></i><span data-i18n="emp_modal_nuevo">Registrar Empleado</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" id="btnCerrarModalX" aria-label="Close"></button>
            </div>
            <form id="formEmpleado">
                <div class="modal-body">
                    <input type="hidden" id="id_empleado" name="id_empleado">

                    <div class="mb-3">
                        <label for="identidad" class="form-label font-weight-bold" data-i18n="emp_lbl_identidad">N° de Identidad</label>
                        <input type="text" class="form-control" id="identidad" name="identidad" placeholder="Ej. 0801199000000" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label font-weight-bold" data-i18n="emp_lbl_nombre">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej. Carlos Mendoza" required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label font-weight-bold" data-i18n="emp_lbl_correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@ferreteria.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label font-weight-bold" data-i18n="emp_lbl_rol">Rol del Sistema</label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="" disabled selected data-i18n="emp_opt_select_rol">Seleccione un rol...</option>
                            <option value="Administrador" data-i18n="emp_opt_admin">Administrador</option>
                            <option value="Normal" data-i18n="emp_opt_normal">Normal</option>
                        </select>
                    </div>

                    <div class="row" id="seccionClaves">
                        <div class="col-md-6 mb-3">
                            <label for="clave" class="form-label" data-i18n="emp_lbl_clave">Contraseña</label>
                            <input type="password" class="form-control" id="clave" name="clave" placeholder="••••••••">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="confirmaclave" class="form-label" data-i18n="emp_lbl_confirmaclave">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmaclave" name="confirmaclave" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="btnCancelarModal" data-i18n="emp_btn_cancelar">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarEmpleado">
                        <i class="bi bi-save me-1"></i> <span data-i18n="emp_btn_guardar">Guardar Empleado</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/idiomas.js"></script>

<script>
    (function () {
        let empleadosData = [];

        function abrirModal() {
            $('#modalEmpleado').css('display', 'block').addClass('show');
            $('body').addClass('modal-open');
        }

        function cerrarModal() {
            $('#modalEmpleado').css('display', 'none').removeClass('show');
            $('body').removeClass('modal-open');
        }

        // 1. LISTAR EMPLEADOS
        function listarEmpleados() {
            $.ajax({
                url: 'user',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                dataType: 'json',
                success: function (response) {
                    let html = '';
                    empleadosData = response.data || response;

                    if (Array.isArray(empleadosData) && empleadosData.length > 0) {
                        empleadosData.forEach((emp, index) => {
                            let esAdmin = (emp.rol === 'Administrador' || emp.rol == '1' || emp.id_rol == '1');
                            let badgeRol = esAdmin
                                ? '<span class="badge bg-danger-subtle text-danger" data-i18n="emp_opt_admin">Administrador</span>' 
                                : '<span class="badge bg-info-subtle text-info" data-i18n="emp_opt_normal">Normal</span>';

                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${emp.dni || emp.identidad || '-'}</td>
                                    <td class="fw-semibold">${emp.nombre}</td>
                                    <td>${emp.email || emp.correo}</td>
                                    <td>${badgeRol}</td>
                                    <td>${emp.fecha_registro || '-'}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1 btn-editar" data-id="${emp.id_empleado || emp.id}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar" data-id="${emp.id_empleado || emp.id}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = '<tr><td colspan="7" class="text-center py-4 text-muted" data-i18n="emp_msg_sin_datos">No se encontraron empleados registrados.</td></tr>';
                    }
                    $('#tbodyEmpleados').html(html);

                    // Traducir los badges y mensajes generados dinámicamente
                    if (typeof traducirPagina === 'function') traducirPagina();
                },
                error: function () {
                    $('#tbodyEmpleados').html('<tr><td colspan="7" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle me-2"></i><span data-i18n="emp_msg_error_consulta">Error al consultar los empleados.</span></td></tr>');
                    if (typeof traducirPagina === 'function') traducirPagina();
                }
            });
        }

        listarEmpleados();

        // 2. BOTÓN NUEVO EMPLEADO
        $(document).off('click', '#btnNuevoEmpleado').on('click', '#btnNuevoEmpleado', function (e) {
            e.preventDefault();
            $('#formEmpleado')[0].reset();
            $('#id_empleado').val('');
            
            $('#modalEmpleadoLabel').html('<i class="bi bi-person-plus me-2"></i><span data-i18n="emp_modal_nuevo">Registrar Empleado</span>');
            $('#clave, #confirmaclave').prop('required', true);
            
            if (typeof traducirPagina === 'function') traducirPagina();
            abrirModal();
        });

        // 3. CERRAR MODAL
        $(document).off('click', '#btnCancelarModal, #btnCerrarModalX').on('click', '#btnCancelarModal, #btnCerrarModalX', function (e) {
            e.preventDefault();
            cerrarModal();
        });

        // 4. BOTÓN EDITAR
        $(document).off('click', '.btn-editar').on('click', '.btn-editar', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let emp = empleadosData.find(e => (e.id_empleado == id || e.id == id));

            if (emp) {
                $('#id_empleado').val(emp.id_empleado || emp.id);
                $('#identidad').val(emp.dni || emp.identidad);
                $('#nombre').val(emp.nombre);
                $('#correo').val(emp.email || emp.correo);

                // Seleccionar correctamente el valor del rol
                let valRol = emp.rol;
                if (valRol == '1') valRol = 'Administrador';
                if (valRol == '2') valRol = 'Normal';
                $('#rol').val(valRol);

                $('#clave, #confirmaclave').val('').prop('required', false);
                $('#modalEmpleadoLabel').html('<i class="bi bi-pencil-square me-2"></i><span data-i18n="emp_modal_editar">Editar Empleado</span>');
                
                if (typeof traducirPagina === 'function') traducirPagina();
                abrirModal();
            }
        });

        // 5. GUARDAR Y EDITAR (SUBMIT)
        $(document).off('submit', '#formEmpleado').on('submit', '#formEmpleado', function (e) {
            e.preventDefault();

            let id = $('#id_empleado').val();
            let clave = $('#clave').val();
            let confirmaclave = $('#confirmaclave').val();
            let rolSeleccionado = $('#rol').val();

            if (clave !== confirmaclave) {
                Swal.fire('Atención', 'Las contraseñas no coinciden.', 'warning');
                return;
            }

            let idRolNum = (rolSeleccionado === 'Administrador' || rolSeleccionado === '1') ? '1' : '2';

            let payload = {
                nombre: $('#nombre').val().trim(),
                dni: $('#identidad').val().trim(),
                email: $('#correo').val().trim(),
                rol: rolSeleccionado,
                id_rol: idRolNum,
                clave: clave,
                confirmarclave: confirmaclave
            };

            let urlDestino = id ? 'user/' + id : 'user';
            let metodo = id ? 'PUT' : 'POST';

            $.ajax({
                url: urlDestino,
                type: metodo,
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(payload),
                dataType: 'json',
                success: function (res) {
                    cerrarModal();
                    listarEmpleados();
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: id ? 'Empleado actualizado correctamente.' : 'Empleado registrado con éxito.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function (xhr) {
                    let msg = 'Error al procesar la solicitud.';
                    if (xhr.responseJSON && xhr.responseJSON.data) {
                        msg = xhr.responseJSON.data;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // 6. BOTÓN ELIMINAR (CON ALERTA SWEETALERT2)
        $(document).off('click', '.btn-eliminar').on('click', '.btn-eliminar', function (e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción no se puede deshacer y eliminará al empleado del sistema.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'user/' + id,
                        type: 'DELETE',
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        dataType: 'json',
                        success: function (res) {
                            listarEmpleados();
                            Swal.fire(
                                '¡Eliminado!',
                                'El empleado ha sido eliminado correctamente.',
                                'success'
                            );
                        },
                        error: function (xhr) {
                            let msg = 'No se pudo eliminar el empleado.';
                            if (xhr.responseJSON && xhr.responseJSON.data) {
                                msg = xhr.responseJSON.data;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', msg, 'error');
                        }
                    });
                }
            });
        });

        let idiomaActual = localStorage.getItem('idioma') || 'es';
                $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

                $('#btn-idioma').on('click', function (e) {
                    e.preventDefault();
                    let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
                    localStorage.setItem('idioma', nuevo);
                    location.reload();
                });
    })();
</script>