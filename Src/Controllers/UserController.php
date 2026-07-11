<?php

namespace App\Controllers;

// Importaciones fundamentales para que PHP encuentre las clases
use App\Models\userModel;
use App\Config\responseHTTP;

class UserController {
    private static $method; 
    private static $route;
    private static $params;
    private static $data;
    private static $headers; 
    private static $validar_rol = '/^[1,2,3]{1,1}$/'; 
    private static $validar_numero = '/^[0-9]+$/'; 
    private static $validar_texto = '/^[a-zA-Z ]+$/'; // Añadido espacio para nombres completos


    public function __construct($method, $route, $params, $data, $headers) {
        self::$method = $method;      
        self::$route = $route;
        self::$params = $params;
        self::$data = $data;
        self::$headers = $headers;            
    }

    final public function registrarUsuario($endpoint) {
        
        if (self::$method == 'post' && $endpoint == self::$route) {
            
            // 1. Validamos que los campos requeridos no vengan vacíos
            if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmaclave'])) {
                
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                exit;
            } 
            
            // 2. Validamos expresiones regulares para texto (Nombre)
            else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                exit;
            } 
            
            // 3. Validamos expresiones regulares para números (DNI)
            else if (!preg_match(self::$validar_numero, self::$data['dni'])) {
                echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                exit;
            } 
            
            // 4. Validamos formato de correo electrónico
            else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                exit;
            } 
            
            // 5. Validar que el rol sea obligatoriamente Administrador o Normal
            else if (self::$data['rol'] !== 'Administrador' && self::$data['rol'] !== 'Normal') {
                echo json_encode(responseHTTP::status400('El rol es invalido. Debe ser Administrador o Normal.'));
                exit;
            }
            
            // 6. Si pasa todo, mandamos los datos estáticos correctos al Modelo
            else {
                new userModel(self::$data); // ¡Corregido de $this->data a self::$data!
                echo json_encode(userModel::post());
                exit;
            }
            
        }
    }

<<<<<<< HEAD
     final public function getLogin($endpoint){

     //validamos method y endpoint 
        if(self::$method == 'get' && $endpoint == self::$route){ 
          
        $email = strtolower(self::$params[1]); //pasamos el email
           $pass = self::$params[2]; //pasamos la clave
            //algunas validaciones requeridas
            if(empty($email) || empty($pass)){
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a
                llenarlos.'));
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
            }else{
                //pasamos los val al modelo que usaremos para hacer la peticion a la BD y llamamos al metodo Login
                UserModel::setEmail($email);
                UserModel::setClave($pass);
                echo json_encode(UserModel::Login());
            }
            exit;
=======
    final public static function getLogin($endpoint){
        if(self::$method == 'get' && $endpoint == self::$params[0]){
            ///echo "ingresa";
            $user = strtolower(self::$params[1]); 
            $pass = self::$params[2]; 
            if(empty($user) || empty($pass)){
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a
                llenarlos.'));
            }else if(!filter_var($user, FILTER_VALIDATE_EMAIL)){
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
            }else{
                userModel::setCorreo($user);
                userModel::setClave($pass);
                echo json_encode(userModel::Login());
            }
            exit;  
>>>>>>> 93b3d3d86a404d83fde1a1af30e9a3802fbd7ede
        }
    }



    /*Metodo para registrar un usuario en la bd 
    Params: 
        ruta - route*/

    /*    
    final public static function registrarAbogado($endpoint){     
       //echo self::$params[0];
        //validaciones
        if(self::$method == 'post' && $endpoint == self::$params[0]){
            //validamos que los campos no vengan vacios
                if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                    empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmaClave'])) {
                    echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                    //validamos que los campos de texto sean de texto mediante preg_match evaluamos expresiones regulares
                } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                    echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                    //validamos que los campos numericos sean contengan solo numeros mediante preg_match evaluamos expresiones regulares
                } else if (!preg_match(self::$validar_numero,self::$data['dni'])) {
                    echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                    //validamos que el correo tenga el formato correcto 
                    //filter_var permite crear un filtro especifico y luego validar a partir de este
                }  else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                    //validamos el rol 
                }else if (!preg_match(self::$validar_rol,self::$data['rol'])) {
                    echo json_encode(responseHTTP::status400('El rol es invalido'));
                } else {
                    new UserModel(self::$data); //creamos un objeto de la clase UserModel y le pasamos los datos del usuario
                    echo json_encode(UserModel::registrarAbogado());
                }
            exit;
        }
    }

    /* Metodo para actualizar un abogado en la bd */
    /*
    final public static function actualizarAbogado($endpoint){
        // Validamos que el método HTTP sea PUT y que coincida el endpoint
        if(self::$method == 'put' && $endpoint == self::$params[0]){
            
            // Validamos que los campos obligatorios no vengan vacíos en el body (self::$data)
            if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || empty(self::$data['rol'])) {
                echo json_encode(ResponseHTTP::status400('Todos los campos son requeridos para actualizar.'));
            } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                echo json_encode(ResponseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
            } else if (!preg_match(self::$validar_numero, self::$data['dni'])) {
                echo json_encode(ResponseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
            } else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(ResponseHTTP::status400('El correo debe tener el formato correcto.'));
            } else {
                // Pasamos los datos recibidos en el JSON creando una instancia o usando setters
                new UserModel(self::$data); 
                
                // Retornamos lo que resuelva el modelo en su método de actualización
                echo json_encode(UserModel::actualizarAbogado());
            }
            exit;
        }
    }

    /* Metodo para eliminar un abogado de la bd */
    /*
    final public static function eliminarAbogado($endpoint){
        // Validamos que el método sea DELETE y coincida el endpoint inicial
        if(self::$method == 'delete' && $endpoint == self::$params[0]){
            
            // El DNI vendría en la segunda posición de los parámetros de la URL: /eliminarAbogado/{dni}
            $dni = self::$params[1];
            
            if(!isset($dni) || empty($dni)){
                echo json_encode(ResponseHTTP::status400('Debe ingresar el DNI del abogado a eliminar.'));
            } else if(!preg_match(self::$validar_numero, $dni)){
                echo json_encode(ResponseHTTP::status400('El DNI debe contener solo numeros.'));
            } else {
                // Le asignamos el DNI al modelo para que sepa qué registro borrar
                UserModel::setDni($dni);
                
                // Ejecutamos la consulta de eliminación en el modelo
                echo json_encode(UserModel::eliminarAbogado());
            }
            exit;
        }
    }

    
/*
    final public static function getAllUsers($endpoint){
        //validamos method y endpoint 
        if(self::$method == 'get' && $endpoint == self::$route){ 
            //validamos JWT, enviando header y clave secreta
            //Security::validateTokenJwt($this->headers, Security::secretKey());  
            echo json_encode(userModel::getAll());            
            exit;
        }
    }

     final public function getUser($endpoint){        
             //validamos method y endpoint              
        if($this->method == 'get' && $endpoint == $this->route){ 
            //validamos JWT, enviando header y clave secreta
           // Security::validateTokenJwt($this->headers, Security::secretKey());  
            $dni = $this->params[1];
            if(!isset($dni)){
                echo json_encode(responseHTTP::status400('Debe ingresar el DNI para proceder!'));
            } else if(!preg_match(self::$validar_numero, $dni)){
                echo json_encode(responseHTTP::status400('El DNI debe contener solo numeros!'));
            }else{
                userModel::setDni($dni);
                echo json_encode(userModel::getUser());         
                exit;
            }            
        }
    }
    
    //metodo para actualizar la contraseña (actualizar parcialmente un recurso PATCH)
    final public function patchPassword($endpoint){
        if($this->method == 'patch' && $endpoint == $this->route){                
            //validamos JWT, enviando header y clave secreta
            //Security::validateTokenJwt($this->headers, Security::secretKey());  
            
            //validamos los campos necesarios
            if(empty($this->data['oldPassword']) || empty($this->data['newPassword']) || empty($this->data['confirmPassword'])){
                echo json_encode(responseHTTP::status400('Debe llenar todos los campos para proceder!'));
            //validamos la contraseña anterior (debe ser correcta)
            } elseif(!userModel::validateOldPassword($this->data['IDToken'], $this->data['oldPassword'])){
                echo json_encode(responseHTTP::status400('La contraseña anterior es incorrecta!'));
            //validamos que el DNI tenga solo numeros
            } else if(!preg_match(self::$validar_numero, $dni)){
                echo json_encode(responseHTTP::status400('El DNI debe contener solo numeros!'));
            }else{
                userModel::setDni($dni);
                echo json_encode(userModel::getUser());         
                exit;
            }            
        }
    }*/
}
