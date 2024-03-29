<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/producto.php';
 
$data = $_GET['codigo'];

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar listado
    $producto = new Producto($db);

    // Setear valored de nombre para buscar en el where
    $producto->codigo = $data;

    //Ejecutamos
    $stmt = $producto->getProductScanned();

    //Contamos si hay filas encontradas
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){
    
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode(
            array(
                "idProducto" => (int) $producto->idProducto,
                "idCategoria" => (int) $producto->IdCategoriaFK,
                "descripcion" => $producto->descripcion,
                "categoria" => $producto->categoria,
                "marca" => $producto->marca,
                "precio" => (double) $producto->precio,
            )
        );
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