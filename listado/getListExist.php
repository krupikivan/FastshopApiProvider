<?php

// required headers
header("Access-Control-Allow-Origin: http://localhost/api/");
//header("Access-Control-Allow-Origin: http://app-1538168783.000webhostapp.com/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Obtener archivos para la conexion
include_once '../config/database.php';
include_once '../objects/listado.php';

//Obtener datos de POST desde un JSON
//$data = json_decode(file_get_contents("php://input"));
$data = $_GET['idListado'];

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar listado
    $listado = new Listado($db);

    // Setear valored de nombre para buscar en el where
    $listado->idListado = $data;

    //Ejecutamos
    $stmt = $listado->getList();

    //Contamos si hay filas encontradas
    $num = $stmt->rowCount();
    
    $list_arr=array();
     
            $list_item=array(
                "idListado" => $id,
                "nombre" => $listado->nombre,
            );
            array_push($list_arr, $list_item);

    // check if more than 0 record found
    if($num>0){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($list_arr)
    }
    
    else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode($list_arr)
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    //Se requieren datos
    echo json_encode(false);
}
?>