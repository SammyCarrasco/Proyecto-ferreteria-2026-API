<?php

namespace App\Controllers;

// Importaciones fundamentales para que PHP encuentre las clases
use App\Models\userModel;
use App\Config\responseHTTP;
use App\Config\Security;
use App\Config\ErrorLogs;

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


    final public static function registrarUsuario($endpoint){     
       //echo self::$params[0];
        //validaciones
        if(self::$method == 'post' && $endpoint == self::$params[0]){
           //security::validateTokenJwt(self::$headers, Security::secretKey()); //validamos JWT, enviando header y clave secreta    
        //validamos que los campos no vengan vacios

                if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                    empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmarclave'])) {
                    echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                    exit;
                    //validamos que los campos de texto sean de texto mediante preg_match evaluamos expresiones regulares
                } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                    echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                    exit;
                    //validamos que los campos numericos sean contengan solo numeros mediante preg_match evaluamos expresiones regulares
                } else if (!preg_match(self::$validar_numero,self::$data['dni'])) {
                    echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                    exit;
                    //validamos que el correo tenga el formato correcto 
                    //filter_var permite crear un filtro especifico y luego validar a partir de este
                }  else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                    exit;
                    //validamos el rol 
                }else if (!preg_match(self::$validar_rol,self::$data['rol'])) {
                    echo json_encode(responseHTTP::status400('El rol es invalido'));
                    exit;
                } 
               new userModel(self::$data); //creamos un objeto de la clase UserModel y le pasamos los datos del usuario
                echo json_encode(userModel::registrarUsuario());
                exit;
        
        }
       
    }


    final public function getLogin($endpoint){
       // print_r(self::$params);
    
        if(self::$method == 'get' && $endpoint == self::$params[0]){
            //$user = strtolower(self::$params[1]); 
            $email = strtolower(self::$params[1]);
            $pass = self::$params[2]; 
            if(empty($email) || empty($pass)){
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a
                llenarlos.'));
            }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
            }else{
                userModel::setCorreo($email);
                userModel::setClave($pass);
                echo json_encode(userModel::Login());
            }
            exit;  
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

    /* Metodo para actualizar un usuario en la bd */
    
     final public static function actualizarUsuario($endpoint){   
        //validaciones
       // print_r(self::$params);
        //print_r(self::$data);
         //print_r(self::$endpoint);
        
        if(self::$method == 'put' && self::$params[0] == 'user'){ //validamos que el metodo sea put y que la ruta sea user
            //validamos que los campos no vengan vacios
            if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                    empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmarclave'])) {
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                exit;
                //validamos que los campos de texto sean de texto mediante preg_match evaluamos expresiones regulares
            } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                exit;            
                //validamos que los campos numericos sean contengan solo numeros mediante preg_match evaluamos expresiones regulares
            } else if (!preg_match(self::$validar_numero,self::$data['dni'])) {
                echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                exit;
                //validamos que el correo tenga el formato correcto 
                //filter_var permite crear un filtro especifico y luego validar a partir de este
            }  else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                exit;
                //validamos el rol 
            }else if (!preg_match(self::$validar_rol,self::$data['rol'])) {
                echo json_encode(responseHTTP::status400('El rol es invalido'));
            } else {
    // 1. Capturamos el ID de la URL (ej: /user/1 -> $id = 1)
    $id = self::$params[1] ?? null;

    if (!$id) {
        echo json_encode(responseHTTP::status400('ID no proporcionado en la URL.'));
        exit;
        } 
        new userModel(self::$data); //creamos un objeto de la clase UserModel y le pasamos los datos del usuario
        echo json_encode(userModel::actualizarUsuario($id)); //llamamos al metodo actualizarUsuario de la clase userModel y le pasamos el id del usuario a actualizar
        exit;
    }
        exit;
        }
    }

    /* Metodo para eliminar un usuario  de la bd */
    
    final public static function eliminarUsuario($endpoint){
        // Validamos que el método sea DELETE y coincida el endpoint inicial
        if(self::$method == 'delete' && $endpoint == self::$params[0]){
            
            // El DNI vendría en la segunda posición de los parámetros de la URL: /eliminarUsuario/{dni}
            $dni = self::$params[1] ?? null; // Usamos null como valor por defecto si no se proporciona el parámetro
            
            if(!isset($dni) || empty($dni)){
                echo json_encode(ResponseHTTP::status400('Debe ingresar el DNI del usuario a eliminar.'));
            } else if(!preg_match(self::$validar_numero, $dni)){
                echo json_encode(ResponseHTTP::status400('El DNI debe contener solo numeros.'));
            } else {
                // Le asignamos el DNI al modelo para que sepa qué registro borrar
                // UserModel::setDni($dni);
                
                // Ejecutamos la consulta de eliminación en el modelo
                echo json_encode(UserModel::eliminarUsuario($dni));
            }
            exit;
        }
    }

    

    final public static function getAll($endpoint){
        //validamos method y endpoint 

        if(self::$method == 'get' && $endpoint == self::$route){ 
            //validamos JWT, enviando header y clave secreta
        
            //Security::validateTokenJwt($this->headers, Security::secretKey());  

            $data = userModel::getAll(); //llamamos al metodo getAll de la clase userModel
            if($data){
                echo json_encode([
                    'status' => 200,
                    'message' => 'Usuarios encontrados',
                    'data' => $data
                ]);
            }else{
                echo json_encode([
                    'status' => 200,
                    'message' => 'No se encontraron usuarios',
                    'data' => []
                ]);
            }
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
    }
}
