<?php

      namespace App\Controllers;
      use App\Models\clienteModel;
      class ClienteController{

        private static $method;
        private static $params;
        private static $data;

    public function __construct(
      $method,
      $route,
      $params,
      $data,
      $headers
){

      self::$method=$method;
      self::$params=$params;
      self::$data=$data;

}

      public function registrarCliente(){
      if(self::$method=="post"){
      new clienteModel(self::$data);
      echo json_encode(
      clienteModel::registrarCliente()
);

exit;

    }

}

        public function getAll(){
         if(self::$method=="get"){

             echo json_encode(
             clienteModel::getAll()
 );

      exit;

  }

 }

     public function actualizarCliente(){
        if(self::$method=="put"){
           $id=self::$params[1];
           new clienteModel(self::$data);
           echo json_encode(
        clienteModel::actualizarCliente($id)
);
exit;
}
}

       public function eliminarCliente(){
          if(self::$method=="delete"){
                 $id=self::$params[1];
                 echo json_encode(
                 clienteModel::eliminarCliente($id)
);

exit;
    }
  }
}