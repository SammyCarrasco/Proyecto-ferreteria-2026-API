<?php

namespace App\Models;

use App\BD\connectionDB;
use App\Config\responseHTTP;


class clienteModel extends connectionDB {


    private static $rtn;
    private static $nombre;
    private static $telefono;
    private static $fecha_registro;


    public function __construct(array $data){

        self::$rtn = $data['rtn'] ?? '';
        self::$nombre = $data['nombre'] ?? '';
        self::$telefono = $data['telefono'] ?? '';
        self::$fecha_registro = $data['fecha_registro'] ?? '';

    }

    final public static function getRtn(){
        return self::$rtn;
    }

    final public static function getNombre(){
        return self::$nombre;
    }

    final public static function getTelefono(){
        return self::$telefono;
    }

    // POST REGISTRAR CLIENTE

    final public static function registrarCliente(){

        try{

            $con=self::getConnection();
            $query="
            CALL RegistrarCliente(
            :rtn,
            :nombre,
            :telefono
            )";

            $stmt=$con->prepare($query);
            $stmt->execute([

                ':rtn'=>self::getRtn(),
                ':nombre'=>self::getNombre(),
                ':telefono'=>self::getTelefono(),
            
            ]);

            return responseHTTP::status200(
                "Cliente registrado exitosamente"
            );

        }catch(\PDOException $e){
            error_log("clienteModel::registrarCliente ".$e);
            return responseHTTP::status500();

        }

    }

    // GET CLIENTES

    final public static function getAll(){

        try{

            $con=self::getConnection();

            $query="CALL ConsultarClientes()";


            $stmt=$con->prepare($query);

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        }catch(\PDOException $e){

            error_log("clienteModel::getAll ".$e);

            return [];
        }

    }

    // UPDATE CLIENTE

    final public static function actualizarCliente($id){
        try{
            $con=self::getConnection();
            $query="
            CALL ActualizarCliente(
            :id_cliente,
            :rtn,
            :nombre,
            :telefono
            )";

            $stmt=$con->prepare($query);
            $stmt->execute([

                ':id_cliente'=>$id,
                ':rtn'=>self::getRtn(),
                ':nombre'=>self::getNombre(),
                ':telefono'=>self::getTelefono()

            ]);

            return responseHTTP::status200(
                "Cliente actualizado correctamente"
            );

        }catch(\PDOException $e){

            error_log("clienteModel::actualizarCliente ".$e);

            return responseHTTP::status500();

        }

    }

    // DELETE CLIENTE

    final public static function eliminarCliente($id){

        try{
            $con=self::getConnection();

            $query="
            CALL EliminarCliente(:id_cliente)
            ";

            $stmt=$con->prepare($query);


            $stmt->execute([

                ':id_cliente'=>$id

            ]);

            return responseHTTP::status200(
                "Cliente eliminado correctamente"
            );

        }catch(\PDOException $e){

            error_log("clienteModel::eliminarCliente ".$e);

            return responseHTTP::status500();

        }

    }
}