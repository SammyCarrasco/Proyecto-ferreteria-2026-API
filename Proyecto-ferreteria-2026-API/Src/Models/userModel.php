<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;
use App\Config\Security;

class userModel extends connectionDB {
    // Atributos estáticos correspondientes a la tabla empleados de la ferretería
    
    private static $nombre;
    private static $identidad;
    private static $correo;
    private static $rol;
    private static $clave;
    private static $confirmarclave;
    private static $IDToken;
    private static $fecha_registro;

    // Constructor que recibe el array de datos desde el Controlador
    public function __construct(array $data) {
        self::$nombre         = $data['nombre'] ?? '';
        self::$identidad      = $data['dni'] ?? ''; 
        self::$correo         = $data['email'] ?? ''; 
        self::$rol            = $data['rol'] ?? '';
        self::$clave          = $data['clave'] ?? '';
        self::$confirmarclave  = $data['confirmarclave'] ?? '';
        self::$IDToken        = $data['IDToken'] ?? '';
        self::$fecha_registro = $data['fecha'] ?? '';
    }

    // Métodos GET estáticos finales
    final public static function getNombre()        { return self::$nombre; }
    final public static function getIdentidad()     { return self::$identidad; }
    final public static function getCorreo()        { return self::$correo; }
    final public static function getRol()           { return self::$rol; }
    final public static function getClave()         { return self::$clave; }
    final public static function getConfirmarclave() { return self::$confirmarclave; }
    final public static function getIDToken()       { return self::$IDToken; }
    final public static function getFechaRegistro() { return self::$fecha_registro; }

    // Métodos SET estáticos finales
    final public static function setNombre($nombre)                 { self::$nombre = $nombre; }
    final public static function setIdentidad($identidad)           { self::$identidad = $identidad; }
    final public static function setCorreo($correo)                 { self::$correo = $correo; }
    final public static function setRol($rol)                       { self::$rol = $rol; }
    final public static function setClave($clave)                   { self::$clave = $clave; }
    final public static function setConfirmarclave($confirmarclave)   { self::$confirmarclave = $confirmarclave; }
    final public static function setIDToken($IDToken)               { self::$IDToken = $IDToken; }
    final public static function setFechaRegistro($fecha_registro)  { self::$fecha_registro = $fecha_registro; }

    /**
     * Método POST para registrar un empleado usando Procedimientos Almacenados
     */
    final public static function registrarUsuario() {
        // Uso de ruta absoluta global \App\BD\sql para total compatibilidad
        // En vez de tener 3 argumentos, usa solo 2:
        if (\App\BD\sql::verificarRegistro('CALL ConsultarEmpleadoIdentidad(:identidad)', [':identidad' => self::getIdentidad()])) {
            return responseHTTP::status400('La identidad ya esta registrada en la BD');
        } else if (\App\BD\sql::verificarRegistro('CALL ConsultarEmpleadoCorreo(:correo)', [':correo' => self::getCorreo()])) {
            return responseHTTP::status400('El correo ya esta registrado en la BD');
        } else {
            self::setIDToken(hash('sha512', self::getIdentidad() . self::getCorreo()));
            self::setFechaRegistro(date("d-m-y H:i:s")); //fecha de creacion
            try {
                    $con = self::getConnection();
                    
                    // CORRECCIÓN: Un solo signo de dólar ($)
                    $query = "CALL RegistrarEmpleado(:nombre, :identidad, :correo, :rol, :clave, :confirmarclave, :IDToken, :fecha_registro)";
                    $stmt = $con->prepare($query);
                    $stmt->execute([
                        ':nombre'         => self::getNombre(),        
                        ':identidad'      => self::getIdentidad(),     
                        ':correo'         => self::getCorreo(),        
                        ':rol'            => self::getRol(),           
                        ':clave'          => password_hash(self::getClave(), PASSWORD_DEFAULT), // CON PARÉNTESIS
                        ':confirmarclave'  => self::getConfirmarclave(), 
                        ':IDToken'        => self::getIDToken(),       
                        ':fecha_registro' => self::getFechaRegistro()  
                    ]);
                    if ($stmt->rowCount() > 0) {
                        return responseHTTP::status200('Se ha registrado el empleado exitosamente!!!');
                    } else {
                        return responseHTTP::status500('Error al registrar el empleado en la base de datos.');
                    }
                } catch (\PDOException $e) {
                    error_log('userModel::registrarUsuario -> ' . $e->getMessage());
                    // Retornamos un array estructurado que luego el controlador codificará a JSON
                    return ["status" => "error", "message" => "Error de BD: " . $e->getMessage()];
                }
            }
        } 
    

    final public static function login(){
        try {
            $con = self::getConnection(); 
            $query = "CALL Login(:email)";
            $stmt = $con->prepare($query);
            $stmt->execute([':email' => self::getCorreo()]);    
            if($stmt->rowCount() == 0){ 
                return responseHTTP::status400('Usuario o Contraseña incorrectas!!!');
            }else{ 
                foreach ($stmt as $val) {                 
                    if(Security::validatePassword(self::getClave(), $val['clave'])){
                        $payload =['IDToken' => $val['IDToken']];
                        $token = Security::createTokenJwt(Security::secretKey(),$payload);
                        $data = [
                            'nombre' => $val['nombre'],
                            'rol' => $val['rol'],
                            'token' => $token,
                        ];
                        return($data);
                        //retorno la data 
                    }else{
                        return responseHTTP::status400('Usuario o Contraseña incorrectas1!!!');
                    }
                }
            }
        } catch (\PDOException $e) {
            error_log("userModel::Login -> ".$e);
            die(json_encode(responseHTTP::status500()));
        }
    }

    final public static function getAll(){
           try {
                $con = self::getConnection(); //abrimos conexion
                $query = "CALL ConsultarEmpleados()"; //hacemos la consulta para validar la info
                $stmt = $con->prepare($query); //preparamos query
                $stmt->execute();
                $res = $stmt->fetchAll(\PDO::FETCH_ASSOC); //pasamos los resultados a un array
                return $res;
                    
           } catch (\PDOException $e) {
                error_log("userModel::getALL -> ".$e);
                die(json_encode(responseHTTP::status500()));
           }
    }

      final public static function actualizarUsuario($id){
       try {
        $con = self::getConnection();
        // Nota: Ajusta este SP según lo que definiste en tu base de datos
        $query = "CALL ActualizarEmpleado(:id_empleado, :nombre, :identidad, :correo, :rol, :clave, :confirmarclave)";
        $stmt = $con->prepare($query);
        
        $stmt->execute([
            ':id_empleado' => $id,
            ':nombre' => self::getNombre(),        
            ':identidad'      => self::getIdentidad(),     
            ':correo'         => self::getCorreo(),        
            ':rol'            => self::getRol(),           
            ':clave'          => password_hash(self::getClave(), PASSWORD_DEFAULT), // CON PARÉNTESIS
            ':confirmarclave'  => self::getConfirmarclave(), 
          
            ]);

           // print_r($stmt);
           // print_r($stmt->rowCount());
            if($stmt->rowCount() == 0){ //contamos los registros retornados
                return responseHTTP::status400('No se pudo actualizar el usuario, verifique por favor!!!');
            }else{ //si vienen datos
                return responseHTTP::status200('Se ha actualizado el usuario exitosamente!!!');
            }
            return 0;
        } catch (\PDOException $e) {
            error_log("userModel::actualizarUsuario -> ".$e);
            return(json_encode(responseHTTP::status500()));
        }
    }

     final public static function eliminarUsuario($id){
        try {
            $con = self::getConnection(); //abrimos conexion
            $query = "CALL EliminarEmpleado(:idEmpleado)"; //hacemos la consulta para validar la info
            $stmt = $con->prepare($query); //preparamos query
            $stmt->execute([ //pasamos los parametros
                        ':idEmpleado' => $id
                    ]);
           // print_r($stmt);
           // print_r($stmt->rowCount());
            if($stmt->rowCount() == 0){ //contamos los registros retornados
                return responseHTTP::status400('No se pudo eliminar el usuario, verifique por favor!!!');
            }else{ //si vienen datos
                return responseHTTP::status200('Se ha eliminado el usuario exitosamente!!!');
            }
            //return 0;
        } catch (\PDOException $e) {
            error_log("userModel::eliminarUsuario -> ".$e);
            die(json_encode(responseHTTP::status500()));
        }
    }


}