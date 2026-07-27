<?php
 
use App\BD\ConnectionDB;
 
// Ajusta 'getConnection' si tu clase connectionDB usa otro nombre de método
try {
    $con = ConnectionDB::getConnection();
    $stmt = $con->query("SELECT clave, es, en FROM traducciones");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
    echo json_encode([
        'status' => 200,
        'message' => 'Traducciones obtenidas exitosamente',
        'data' => $data
    ]);
} catch (\PDOException $e) {
    error_log("traducciones -> " . $e->getMessage());
    echo json_encode([
        'status' => 500,
        'message' => 'Error al obtener traducciones',
        'data' => []
    ]);
}
 