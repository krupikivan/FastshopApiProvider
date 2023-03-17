<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/compra.php';
 
$data = $_GET['idCliente'];

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar listado
    $compra = new Compra($db);

    // Setear valored de nombre para buscar en el where
    $compra->idsearch = $data;

    //Ejecutamos
    $stmt = $compra->read();

    //Contamos si hay filas encontradas
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
        $list_arr=array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
     
            $list_item=array(
                "IdCompra" => (int) $IdCompra,
                "fechaCompra" => $fechaCompra,
                "idCliente" => $IdCliente,
                "cantidad" => (int) $cantidad,
                "total" => (double) $total
            );
            array_push($list_arr, $list_item);
        }

        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($list_arr);
    }
    else{
    
        // set response code - 404 Not found
        http_response_code(404);
    
        // tell the user no products found
        echo json_encode(array("message" => "Error."));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    //Se requieren datos
    echo json_encode(false);
}
?>