<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;

class userModel extends connectionDB {
    // Atributos estáticos correspondientes a la tabla empleados de la ferretería
    private static $nombre;
    private static $identidad;
    private static $correo;
    private static $rol;
    private static $clave;
    private static $confirmaclave;
    private static $IDToken;
    private static $fecha_registro;

    // Constructor que recibe el array de datos desde el Controlador
    public function __construct(array $data) {
        self::$nombre         = $data['nombre'] ?? '';
        self::$identidad      = $data['dni'] ?? ''; 
        self::$correo         = $data['email'] ?? ''; 
        self::$rol            = $data['rol'] ?? '';
        self::$clave          = $data['clave'] ?? '';
        self::$confirmaclave  = $data['confirmaclave'] ?? '';
        self::$IDToken        = $data['IDToken'] ?? '';
        self::$fecha_registro = $data['fecha'] ?? '';
    }

    // Métodos GET estáticos finales
    final public static function getNombre()        { return self::$nombre; }
    final public static function getIdentidad()     { return self::$identidad; }
    final public static function getCorreo()        { return self::$correo; }
    final public static function getRol()           { return self::$rol; }
    final public static function getClave()         { return self::$clave; }
    final public static function getConfirmaclave() { return self::$confirmaclave; }
    final public static function getIDToken()       { return self::$IDToken; }
    final public static function getFechaRegistro() { return self::$fecha_registro; }

    // Métodos SET estáticos finales
    final public static function setNombre($nombre)                 { self::$nombre = $nombre; }
    final public static function setIdentidad($identidad)           { self::$identidad = $identidad; }
    final public static function setCorreo($correo)                 { self::$correo = $correo; }
    final public static function setRol($rol)                       { self::$rol = $rol; }
    final public static function setClave($clave)                   { self::$clave = $clave; }
    final public static function setConfirmaclave($confirmaclave)   { self::$confirmaclave = $confirmaclave; }
    final public static function setIDToken($IDToken)               { self::$IDToken = $IDToken; }
    final public static function setFechaRegistro($fecha_registro)  { self::$fecha_registro = $fecha_registro; }

    /**
     * Método POST para registrar un empleado usando Procedimientos Almacenados
     */
    final public static function registrarUsuario() {
        
        // Uso de ruta absoluta global \App\BD\sql para total compatibilidad
        if (\App\BD\sql::verificarRegistro('CALL ConsultarEmpleadoIdentidad(:identidad)', [':identidad' => self::getIdentidad()])) {
            return responseHTTP::status400('La identidad ya esta registrada en la BD');
        } 
        
        else if (\App\BD\sql::verificarRegistro('CALL ConsultarEmpleadoCorreo(:correo)', [':correo' => self::getCorreo()])) {
            return responseHTTP::status400('El correo ya esta registrado en la BD');
        } 
        
        else {
            self::setIDToken(hash('sha512', self::getIdentidad() . self::getCorreo()));
            self::setFechaRegistro(date("Y-m-d H:i:s"));

            try {
                $con = self::getConnection();
                
                $query = "CALL RegistrarEmpleado(:nombre, :identidad, :correo, :rol, :clave, :fecha_registro)";
                
                $stmt = $con->prepare($query);
                
                $stmt->execute([
                    ':nombre'         => self::getNombre(),
                    ':identidad'      => self::getIdentidad(),
                    ':correo'         => self::getCorreo(),
                    ':rol'            => self::getRol(),
                    ':clave'          => password_hash(self::getClave(), PASSWORD_DEFAULT),
                    ':fecha_registro' => self::getFechaRegistro()
                ]);

                if ($stmt->rowCount() > 0) {
                    return responseHTTP::status200('Se ha registrado el empleado exitosamente!!!');
                } else {
                    return responseHTTP::status500('Error al registrar el empleado!!!');
                }

            } catch (\PDOException $e) {
                error_log('userModel::post -> ' . $e->getMessage());
                //die(json_encode(responseHTTP::status500()));
                echo json_encode([
                    "status" => "error",
                    "message" => "Error real de MySQL: " . $e->getMessage()
                ]);
                exit;
            }
        }
    }
}