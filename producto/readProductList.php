<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/producto.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$list = new Producto($db);

// query productos

$stmt = $list->readProductList();

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
 
    // products array
    $list_arr=array();
    
    //Le tuve q sacar el api key porque no me traia las promociones
    //$promo_arr["PromocionesVigentes"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $list_item=array(
            "idProducto" => (int)$idProducto,
            "codigo" => (int)$Codigo,
            "descripcion" => $Descripcion,
            "categoria" => $categoria,
            "marca" => $marca,
            "precio" => (double)$Precio
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
    echo json_encode(
        array("message" => "No se encontraron productos")
    );
}
?>