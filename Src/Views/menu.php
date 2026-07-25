<?php
session_start();

// Si no hay sesión activa, regresa al login
if (empty($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$rolActual = $_SESSION['rol']; // "Administrador" o "Normal" (según tu backend)

/**
 * =====================================================================
 * REGISTRO DE MÓDULOS
 * =====================================================================
 * Cada compañero agrega UNA línea aquí para su módulo. No toques
 * las demás líneas ni la lógica de abajo — así evitamos conflictos
 * de Git entre los 7 colaboradores.
 *
 * clave      => la usa el JS para identificar el módulo (sin espacios)
 * label      => texto que se muestra en el menú
 * icon       => clase de Bootstrap Icons (bi-...)
 * fragmento  => ruta al archivo PHP dentro de form/ que se inyecta
 * grupo      => (opcional) para agrupar visualmente en el sidebar
 * roles      => qué roles pueden ver este módulo (Administrador, Normal)
 * =====================================================================
 */
$modulos = [
    'clientes'        => ['label' => 'Clientes',          'icon' => 'bi-person-lines-fill', 'fragmento' => 'form/form_clientes.php',        'grupo' => 'Ventas',          'roles' => ['Administrador', 'Normal']],
    'cotizacion'      => ['label' => 'Cotizaciones',       'icon' => 'bi-file-earmark-text', 'fragmento' => 'form/form_cotizacion.php',      'grupo' => 'Ventas',          'roles' => ['Administrador', 'Normal']],
    'venta'           => ['label' => 'Ventas',             'icon' => 'bi-cart-check',        'fragmento' => 'form/form_venta.php',           'grupo' => 'Ventas',          'roles' => ['Administrador', 'Normal']],

    'catalogo'        => ['label' => 'Catálogo',           'icon' => 'bi-grid-3x3-gap',       'fragmento' => 'form/form_catalogo.php',        'grupo' => 'Inventario',      'roles' => ['Administrador', 'Normal']],
    'productos'       => ['label' => 'Productos',          'icon' => 'bi-box-seam',          'fragmento' => 'form/form_productos.php',       'grupo' => 'Inventario',      'roles' => ['Administrador']],
    'category'        => ['label' => 'Categorías',         'icon' => 'bi-tags',              'fragmento' => 'form/form_category.php',        'grupo' => 'Inventario',      'roles' => ['Administrador']],
    'inventario'      => ['label' => 'Inventario',         'icon' => 'bi-archive',           'fragmento' => 'form/form_inventario.php',      'grupo' => 'Inventario',      'roles' => ['Administrador']],
    'almacenes'       => ['label' => 'Almacenes',          'icon' => 'bi-building',          'fragmento' => 'form/form_almacenes.php',       'grupo' => 'Inventario',      'roles' => ['Administrador']],
    'adminproductos'  => ['label' => 'Admin. Productos',   'icon' => 'bi-tools',             'fragmento' => 'form/form_adminproductos.php',  'grupo' => 'Inventario',      'roles' => ['Administrador']],

    'user'            => ['label' => 'Empleados',          'icon' => 'bi-people',            'fragmento' => 'form/form_user.php',            'grupo' => 'Administración',  'roles' => ['Administrador']],
    'reportes'        => ['label' => 'Reportes',           'icon' => 'bi-bar-chart',         'fragmento' => 'form/form_reportes.php',        'grupo' => 'Administración',  'roles' => ['Administrador']],
];

// Filtramos solo los módulos permitidos para el rol de la sesión actual
$modulos = array_filter($modulos, function ($m) use ($rolActual) {
    return in_array($rolActual, $m['roles']);
});

// Agrupamos para pintar el sidebar por secciones
$grupos = [];
foreach ($modulos as $clave => $m) {
    $grupos[$m['grupo']][$clave] = $m;
}
?>
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
            width: 250px; min-height: 100vh; background: #1e2537; position: fixed; left: 0; top: 0;
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

    <div class="sidebar">
        <div class="brand"><i class="bi bi-hammer"></i> Ferretería 2026</div>

        <?php foreach ($grupos as $nombreGrupo => $items): ?>
            <div class="grupo-titulo"><?= htmlspecialchars($nombreGrupo) ?></div>
            <?php foreach ($items as $clave => $m): ?>
                <a class="nav-link" data-modulo="<?= htmlspecialchars($clave) ?>" data-fragmento="<?= htmlspecialchars($m['fragmento']) ?>">
                    <i class="bi <?= htmlspecialchars($m['icon']) ?>"></i>
                    <?= htmlspecialchars($m['label']) ?>
                </a>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <div class="topbar">
        <span id="titulo-modulo">Panel principal</span>
        <span>
            <?= htmlspecialchars($_SESSION['nombre']) ?> (<?= htmlspecialchars($rolActual) ?>)
            &nbsp;|&nbsp;
            <a href="logout.php" class="text-danger text-decoration-none"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </span>
    </div>

    <div class="main-content">
        <div id="contenido-dinamico">
            <div class="alert alert-secondary">Selecciona un módulo del menú para comenzar.</div>
        </div>
    </div>

    <script>
        // Token disponible para que funciones.js lo agregue en el header Authorization de cada petición
        const SESSION_TOKEN = "<?= htmlspecialchars($_SESSION['token']) ?>";
        const SESSION_ROL = "<?= htmlspecialchars($rolActual) ?>";
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/funciones.js"></script>
    <script>
        $(document).ready(function () {
            // Si la URL trae un hash (ej. menu.php#clientes), cargamos ese módulo directo
            if (window.location.hash) {
                let mod = window.location.hash.replace('#', '');
                let link = $('.nav-link[data-modulo="' + mod + '"]');
                if (link.length) link.trigger('click');
            }
        });

        // Delegación de eventos: funciona incluso si en el futuro agregan links dinámicamente
        $(document).on('click', '.nav-link', function () {
            let modulo = $(this).data('modulo');
            let fragmento = $(this).data('fragmento');
            let etiqueta = $(this).text().trim();

            $('.nav-link').removeClass('active');
            $(this).addClass('active');
            $('#titulo-modulo').text(etiqueta);

            cargarModulo(modulo, fragmento);
        });
    </script>
</body>
</html>
