<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/promocion.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$promo = new Promocion($db);

// query products

$stmt = $promo->read();

$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){
 
    // products array
    $promo_arr=array();
    
    //Le tuve q sacar el api key porque no me traia las promociones
    //$promo_arr["PromocionesVigentes"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $promo_item=array(
            "idPromocion" => $IdPromocion,
            "fechaFin" => $FechaFin,
            "fechaInicio" => $FechaInicio,
            "producto" => html_entity_decode($producto),
            "promocion" => html_entity_decode($promocion)
        );
        array_push($promo_arr, $promo_item);
        //Le tuve q sacar el api key porque no me traia las promociones
        //array_push($promo_arr["PromocionesVigentes"], $promo_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($promo_arr);
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No se encontraron promociones vigentes")
    );
}
?>