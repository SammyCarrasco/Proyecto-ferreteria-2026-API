<?php
     use App\Controllers\ClienteController;
     $method=strtolower($_SERVER['REQUEST_METHOD']);
     $route=$_GET['route'];

     $params=explode('/',$route);

     $data=json_decode(
     file_get_contents("php://input"),
   true
);

       $headers=getallheaders();

         $app=new ClienteController(
         $method,
         $route,
         $params,
         $data,
         $headers
);

     $app->registrarCliente();
     $app->getAll();
     $app->actualizarCliente();
     $app->eliminarCliente();
