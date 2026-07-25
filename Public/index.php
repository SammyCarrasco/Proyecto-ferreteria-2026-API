<?php
    require dirname(__DIR__).'/vendor/autoload.php';

    use App\Config\ErrorLogs; 
    use App\Config\ResponseHTTP; 

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    ErrorLogs::activa_error_logs(); 

    if(!isset($_GET['route'])){
        echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
        exit;
    }

    $url = explode('/', $_GET['route']);

    $modulo = basename($url[0], '.php');

    $lista = ['auth', 'user', 'productos', 'category', 'reportes', 'cotizacionDetalle',
     'adminproductos', 'venta', 'inventario', 'almacenes', 'catalogo', 
     'clientes', 'Cotizacion', 'login', 'menu', 'sesion_guardar', 'form'];

    if (!in_array($modulo, $lista)) {
        echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
        exit;
    }

    $caso = filter_input(INPUT_GET, 'caso');

    if ($modulo === 'login') {
        $file = dirname(__DIR__) . '/Src/Views/login.php';
    } else if ($caso !== null && $caso !== false && $caso !== "") {
        // 1. Busca primero en la subcarpeta form con el prefijo form_ (ej: Src/Views/form/form_user.php)
        $fileForm = dirname(__DIR__) . '/Src/Views/form/form_' . $modulo . '.php';

        if (file_exists($fileForm)) {
            $file = $fileForm;
        } else {
            // 2. Si no existe en form/, busca directamente en Src/Views/modulo.php
            $file = dirname(__DIR__) . '/Src/Views/' . $modulo . '.php';
        }
    } else {
        $file = dirname(__DIR__) . '/Src/Routes/' . $modulo . '.php'; 
    }

    if (file_exists($file) && is_readable($file)) {
        require_once $file;
        exit;
    } else {
        echo json_encode(ResponseHTTP::status404("La vista o ruta solicitada no existe."));
        exit;
    }

    


/*	
    //print_r($_GET); 
    use App\Config\ErrorLogs; //importamos la clase ErrorLogs para poder usarla en este archivo
    use App\Config\ResponseHTTP; //importamos la clase ResponseHTTP para poder usarla en este archivo
    require dirname(__DIR__).'/vendor/autoload.php';

    ErrorLogs::activa_error_logs(); //activamos el registro de errores en el archivo php-error.log   
    if(!isset($_GET['route'])){
        echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
        error_log("Ruta no encontrada: " . $_GET['route']); // Registrar el error en el archivo de registro
        exit;
    }else{
        $url = explode('/', $_GET['route']);
        $lista = ['auth', 'users', 'productos']; // lista de rutas permitidas
        $file = dirname(__DIR__) . '/Src/Routes/' . $url[0] . '.php';

        if(!in_array($url[0], $lista)){
            echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
            error_log("Ruta no encontrada: " . $_GET['route']); // Registrar el error en el archivo de registro
            exit;
        }else{
            //echo "La ruta existe";
            if(!file_exists($file) || !is_readable($file)){
                echo json_encode(ResponseHTTP::status404("El recurso solicitado no existe o no se puede leer!"));
                error_log("Recurso no encontrado: " . $_GET['route']); // Registrar el error en el archivo de registro
                exit;
            }else{
            //echo "El recurso existe y se puede leer"
                require $file;  
            }		
            exit;
        }
    }
   
    */
?>