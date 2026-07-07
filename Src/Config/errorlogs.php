<?php

<<<<<<< HEAD
namespace App\Config; 
=======
namespace App\Config;
>>>>>>> 32991b73c151febfde8a6782ec00af82830922e9
class errorlogs{
    public static function activa_error_logs(){
         error_reporting(E_ALL); //activamos todos los errores de php
         // $log = fopen('error_log', 'a+'); //abrimos el archivo de log en modo append

         ini_set('ignore_repeated_errors', TRUE); //ignorar errores repetidos
         ini_set('display_errors', FALSE); //no mostrar errores en pantalla
         ini_set('log_errors', TRUE); //activar el log de errores
         ini_set('error_log', dirname(__DIR__).'/Logs/php-error.log'); //ruta del archivo de log
         date_default_timezone_set('America/Tegucigalpa'); //agregamos la zona horaria
         }
}