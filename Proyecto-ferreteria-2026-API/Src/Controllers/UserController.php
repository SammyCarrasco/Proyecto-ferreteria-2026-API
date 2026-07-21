<?php

namespace App\Controllers;

// Importaciones fundamentales
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
    private static $validar_texto = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';

    public function __construct($method, $route, $params, $data, $headers) {
        self::$method  = $method;      
        self::$route   = $route;
        self::$params  = $params;
        self::$data    = $data;
        self::$headers = $headers;            
    }

    /* 1. REGISTRAR EMPLEADO (POST) */
    final public static function registrarUsuario($endpoint){     
        if(self::$method == 'post' && $endpoint == self::$params[0]){
            // Security::validateTokenJwt(self::$headers, Security::secretKey());
            
            if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmarclave'])) {
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                exit;
            } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                exit;
            } else if (!preg_match(self::$validar_numero, self::$data['dni'])) {
                echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                exit;
            } else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                exit;
            } else if (!preg_match(self::$validar_rol, self::$data['rol'])) {
                echo json_encode(responseHTTP::status400('El rol es invalido.'));
                exit;
            } 

            new userModel(self::$data);
            echo json_encode(userModel::registrarUsuario());
            exit;
        }
    }

    /* 2. LOGIN (GET / AUTH) */
    final public function getLogin($endpoint){
        if(self::$method == 'get' && $endpoint == self::$params[0]){
            $email = strtolower(self::$params[1] ?? '');
            $pass  = self::$params[2] ?? ''; 

            if(empty($email) || empty($pass)){
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
            } else {
                userModel::setCorreo($email);
                userModel::setClave($pass);
                echo json_encode(userModel::login());
            }
            exit;  
        }
    }

    /* 3. ACTUALIZAR EMPLEADO (PUT) */
    final public static function actualizarUsuario($endpoint){   
        if(self::$method == 'put' && self::$params[0] == $endpoint){ 
            if (empty(self::$data['nombre']) || empty(self::$data['dni']) || empty(self::$data['email']) || 
                empty(self::$data['rol']) || empty(self::$data['clave']) || empty(self::$data['confirmarclave'])) {
                echo json_encode(responseHTTP::status400('Todos los campos son requeridos, proceda a llenarlos.'));
                exit;
            } else if (!preg_match(self::$validar_texto, self::$data['nombre'])) {
                echo json_encode(responseHTTP::status400('En el campo nombre debe ingresar solo texto.'));
                exit;            
            } else if (!preg_match(self::$validar_numero, self::$data['dni'])) {
                echo json_encode(responseHTTP::status400('En el campo dni debe ingresar solo numeros.'));
                exit;
            } else if (!filter_var(self::$data['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(responseHTTP::status400('El correo debe tener el formato correcto.'));
                exit;
            } else if (!preg_match(self::$validar_rol, self::$data['rol'])) {
                echo json_encode(responseHTTP::status400('El rol es invalido.'));
                exit;
            } else {
                $id = self::$params[1] ?? null;

                if (!$id) {
                    echo json_encode(responseHTTP::status400('ID no proporcionado en la URL.'));
                    exit;
                } 
                new userModel(self::$data);
                echo json_encode(userModel::actualizarUsuario($id));
                exit;
            }
        }
    }

    /* 4. ELIMINAR EMPLEADO (DELETE) */
    final public static function eliminarUsuario($endpoint){
        if(self::$method == 'delete' && $endpoint == self::$params[0]){
            $id = self::$params[1] ?? null;
            
            if(!isset($id) || empty($id)){
                echo json_encode(responseHTTP::status400('Debe ingresar el ID del usuario a eliminar.'));
            } else if(!preg_match(self::$validar_numero, $id)){
                echo json_encode(responseHTTP::status400('El ID debe contener solo numeros.'));
            } else {
                echo json_encode(userModel::eliminarUsuario($id));
            }
            exit;
        }
    }

    /* 5. CONSULTAR TODOS LOS EMPLEADOS (GET) */
    final public static function getAll($endpoint){
        if(self::$method == 'get' && $endpoint == self::$route){ 
            // Security::validateTokenJwt(self::$headers, Security::secretKey());  

            $data = userModel::getAll();
            if($data){
                echo json_encode([
                    'status' => 200,
                    'message' => 'Usuarios encontrados',
                    'data' => $data
                ]);
            } else {
                echo json_encode([
                    'status' => 200,
                    'message' => 'No se encontraron usuarios',
                    'data' => []
                ]);
            }
            exit;
        }
    }
}