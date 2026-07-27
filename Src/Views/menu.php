<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ferretería 2026 - Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px; height: 100vh; background: #1e2537; position: fixed; left: 0; top: 0;
            overflow-y: auto; padding-top: 15px;
        }
        .sidebar .brand { color: #fff; padding: 10px 20px 20px; font-weight: 600; font-size: 18px; border-bottom: 1px solid #2c3450; }
        .sidebar .grupo-titulo { color: #6b7488; font-size: 11px; text-transform: uppercase; padding: 15px 20px 5px; letter-spacing: .5px; }
        .sidebar .nav-link { color: #c2c7d0; padding: 10px 20px; display: flex; align-items: center; gap: 10px; cursor: pointer; border-left: 3px solid transparent; }
        .sidebar .nav-link:hover { background: #2c3450; color: #fff; }
        .sidebar .nav-link.active { background: #2c3450; color: #fff; border-left-color: #4e73df; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar { background: #fff; padding: 12px 20px; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-left: 250px; display:flex; justify-content: space-between; align-items:center; }
        #contenido-dinamico { margin-top: 20px; }
    </style>
</head>
<body>

    <script>
        
        const SESSION_TOKEN = localStorage.getItem('token');
        const SESSION_ROL = localStorage.getItem('rol') || 'Normal';
        const SESSION_NOMBRE = localStorage.getItem('nombre') || 'Usuario';

        if (!SESSION_TOKEN) {
            window.location.href = 'login';
        }
    </script>

    <div class="sidebar">
        <div class="brand"><i class="bi bi-hammer"></i>El Yunque</div>
<div class="grupo-titulo"><span data-i18n="inicio">Inicio</span></div>

<a class="nav-link active"
   data-modulo="dashboard"
   data-fragmento="form/form_dashboard.php">
    <i class="bi bi-speedometer2"></i>
    Dashboard
</a>

        <div class="grupo-titulo" data-i18n="grupo_ventas"> Ventas</div>
        <a class="nav-link" data-modulo="clientes" data-fragmento="form/form_clientes.php"><i class="bi bi-person-lines-fill"></i> <span data-i18n="menu_clientes">Clientes</span></a>
        <a class="nav-link" data-modulo="cotizacion" data-fragmento="form/form_cotizacion.php"><i class="bi bi-file-earmark-text"></i> <span data-i18n="menu_cotizaciones">Cotizaciones</span></a>
         <a class="nav-link" data-modulo="cotizacion_detalle" data-fragmento="form/form_cotizacion_detalle.php"><i class="bi bi-cart-plus"></i> Detalle Cotización</a>
        <a class="nav-link" data-modulo="venta" data-fragmento="form/form_venta.php"><i class="bi bi-cart-check"></i> <span data-i18n="menu_ventas">Ventas</span></a>

        
        <div class="grupo-titulo" data-i18n="grupo_inventario">Inventario</div>
        <a class="nav-link" data-modulo="catalogo" data-fragmento="form/form_catalogo.php"><i class="bi bi-grid-3x3-gap"></i> <span data-i18n="menu_catalogo">Catálogo</span></a>
        
        <!--<a class="nav-link" data-modulo="productos" data-fragmento="form/form_productos.php" data-solo-admin="1"><i class="bi bi-box-seam"></i> <span data-i18n="menu_productos">Productos</span></a> -->

        <a class="nav-link" data-modulo="category" data-fragmento="form/form_category.php" data-solo-admin="1"><i class="bi bi-tags"></i> <span data-i18n="menu_categorias">Categorías</span></a>
        <a class="nav-link" data-modulo="inventario" data-fragmento="form/form_inventario.php" data-solo-admin="1"><i class="bi bi-archive"></i> <span data-i18n="menu_inventario">Inventario</span></a>
        <a class="nav-link" data-modulo="almacenes" data-fragmento="form/form_almacenes.php" data-solo-admin="1"><i class="bi bi-building"></i> <span data-i18n="menu_almacenes">Almacenes</span></a>
        <a class="nav-link" data-modulo="adminproductos" data-fragmento="form/form_adminproductos.php" data-solo-admin="1"><i class="bi bi-tools"></i> <span data-i18n="menu_adminproductos">Admin. Productos</span></a>

        <div class="grupo-titulo" data-i18n="grupo_administracion">Administración</div>
        <a class="nav-link" data-modulo="user" data-fragmento="form/form_user.php" data-solo-admin="1"><i class="bi bi-people"></i> <span data-i18n="menu_empleados">Empleados</span></a>
        <a class="nav-link" data-modulo="reportes" data-fragmento="form/form_reportes.php" data-solo-admin="1"><i class="bi bi-bar-chart"></i> <span data-i18n="menu_reportes">Reportes</span></a>
    </div>

    <div class="topbar">
        <span id="titulo-modulo" data-i18n="menu_panel_principal">Panel principal</span>
            <a href="#" id="btn-idioma" class="text-decoration-none">🌐 <span id="btn-idioma-texto"></span></a>

        <span>
            <span id="user-info"></span>
            &nbsp;|&nbsp;
            <a href="#" id="btn-logout" class="text-danger text-decoration-none"><i class="bi bi-box-arrow-right"></i> <span data-i18n="menu_salir">Salir</span></a>
        </span>
    </div>

    <div class="main-content">
        <div id="contenido-dinamico">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/idiomas.js"></script>
    <script>
        $(document).ready(function () {
            if (typeof SESSION_NOMBRE !== 'undefined') {
                $('#user-info').text(SESSION_NOMBRE + ' (' + SESSION_ROL + ')');
            }

            // Botón de idioma: muestra el idioma AL QUE VAS A CAMBIAR (no el actual)
            let idiomaActual = localStorage.getItem('idioma') || 'es';
            $('#btn-idioma-texto').text(idiomaActual === 'es' ? 'English' : 'Español');

            $('#btn-idioma').on('click', function (e) {
                e.preventDefault();
                let nuevo = (localStorage.getItem('idioma') || 'es') === 'es' ? 'en' : 'es';
                localStorage.setItem('idioma', nuevo);
                location.reload();
            });

            $('#btn-logout').on('click', function(e) {
                e.preventDefault();
                localStorage.clear();
                window.location.href = 'login';
            });

            if (window.location.hash) {
                let mod = window.location.hash.replace('#', '');
                let link = $('.nav-link[data-modulo="' + mod + '"]');
                if (link.length) link.trigger('click');
            }else {
    $('.nav-link[data-modulo="dashboard"]').trigger('click');
}

        });

        function cargarModulo(modulo, fragmento) {
            $('#contenido-dinamico').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Cargando módulo...</p>
                </div>
            `);

            $.ajax({
                url: modulo + '?caso=1',
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                },
                success: function (data) {
                    $('#contenido-dinamico').html(data);
                },
                error: function (xhr) {
                    console.error("Error al cargar la vista:", xhr);
                    $('#contenido-dinamico').html(`
                        <div class="alert alert-danger shadow-sm my-3">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Error (${xhr.status}):</strong> No se pudo cargar la vista del módulo <b>${modulo}</b>.
                        </div>
                    `);
                }
            });
        }

        $(document).on('click', '.nav-link', function (e) {
            e.preventDefault();

            let modulo = $(this).data('modulo');
            let fragmento = $(this).data('fragmento');
            let etiqueta = $(this).text().trim();
            let soloAdmin = $(this).data('solo-admin');

            if (!modulo) return;

            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('#titulo-modulo').text(etiqueta);

            if (soloAdmin && SESSION_ROL !== 'Administrador') {
                $('#contenido-dinamico').html(`
                    <div class="alert alert-warning shadow-sm my-3">
                        <i class="bi bi-lock-fill me-2"></i>
                        Debes ser <strong>administrador</strong> para acceder a este módulo.
                    </div>
                `);
                return;
            }

            cargarModulo(modulo, fragmento);
        });
    </script>
</body>
</html>
