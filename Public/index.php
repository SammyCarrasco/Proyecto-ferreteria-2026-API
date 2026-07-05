<?php
	require dirname(__DIR__).'/vendor/autoload.php'; 
    use App\Config\errorlogs; 
    use App\Config\ResponseHTTP; 
    errorlogs::activa_error_logs(); //activamos el log de errores
    //require_once __DIR__ . '/../vendor/autoload.php'; //cargamos el autoload de composer
    require dirname(__DIR__). '/vendor/autoload.php'; //cargamos el autoload de composer
   
        if(!isset($_GET['route'])){
        echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
        error_log("Ruta no encontrada: " . $_GET['route']); 
    }else{
        $url = explode('/', $_GET['route']);
        $lista = ['auth', 'users', 'productos']; 
        $file = dirname(__DIR__) . '/Src/Routes/' . $url[0] . '.php';

        if(!in_array($url[0], $lista)){
            echo json_encode(ResponseHTTP::status404("La ruta ingresada no existe!"));
            error_log("Ruta no encontrada: " . $_GET['route']); 
        }else{
            //echo "La ruta existe";
            if(!file_exists($file) || !is_readable($file)){
                echo json_encode(ResponseHTTP::status404("El recurso solicitado no existe o no se puede leer!"));
                error_log("Recurso no encontrado: " . $_GET['route']); 
                exit;
            }else{
                require $file;  
            }		
            exit;
        }
    }
   
    
?>