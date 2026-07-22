<?php
	require dirname(__DIR__).'/vendor/autoload.php';
    //print_r($_GET); 
    use App\Config\ErrorLogs; //importamos la clase ErrorLogs para poder usarla en este archivo
    use App\Config\ResponseHTTP; //importamos la clase ResponseHTTP para poder usarla en este archivo
    
    

    ErrorLogs::activa_error_logs(); //activamos el registro de errores en el archivo php-error.log
   
    if(!isset($_GET['route'])){
        echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
        error_log("Ruta no encontrada: " . $_GET['route']); // Registrar el error en el archivo de registro
        exit;
    }else{
        $url = explode('/', $_GET['route']);
        $lista = ['auth', 'user', 'productos', 'category', 'interfazPrincipal', 'cotizacionDetalle']; // agregamos 'category' a la lista de rutas permitidas
        $file = dirname(__DIR__) . '/Src/Routes/' . $url[0] . '.php';
        $caso = "";
        $file = "";
        $caso = filter_input(INPUT_GET, 'caso');
        if($caso != ""){
            $file = dirname(__DIR__). '/Src/Views/' . $url[0] . '.php';
        }else{
            $file = dirname(__DIR__). '/Src/Routes/' . $url[0] . '.php'; 
        }

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
                //echo "El recurso existe y se puede leer";
                require $file;  
            }		
            exit;
        }
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