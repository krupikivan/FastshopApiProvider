<?php

// required headers
// header("Access-Control-Allow-Origin: http://localhost/api/");
header("Access-Control-Allow-Origin: https://aqueous-fjord-12024.herokuapp.com/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Obtener archivos para la conexion
include_once '../config/database.php';
include_once '../objects/compra.php';
include_once '../objects/producto.php';

//Obtener datos de POST desde un JSON
$getContent = file_get_contents("php://input");
$data = json_decode($getContent);
$json = json_encode($data->listadoProductos);
$array = json_decode($json, true);

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();
    //Instanciar listado
    $compra = new Compra($db);
    $producto = new Producto($db);

    $list = $producto->getProductsCompra($array);
    
    // Get the sum of the prices from $list
    $total = 0;
    // Setear valored de compra
    $compra->idCliente = $data->idCliente;
    $compra->cantidadTotal = count($array);

    // Creamos el compra
// Create array of Compra
    $detalles = array();
    
    for($i=0;$i<count($list);$i++){

        $idProducto = $list[$i]['IdProducto'];

        // Check how many times is the product in the list
        $count = 0;
        foreach ($array as $item) {
            if ($item == $list[$i]['IdProducto']) {
                $count++;
            }
        }
        
        $price = floatval($list[$i]['Precio']); // 10

        $formula = $list[$i]['Formula'];
        $countPurchase = $list[$i]['CantidadProductos']; // 2
        if ($formula > 0) {
            $promotionCount = floor($count / $countPurchase); // 2

            $costWithoutPromotion = $price * $count; // 40

            $priceOnlyPromo = $promotionCount * $price;

            $countLeftProduct = $count - ($promotionCount * $countPurchase);

            $priceLeftProduct = $countLeftProduct * $price;

            $costWithPromotion = $priceOnlyPromo + $priceLeftProduct;

            $discount = number_format($costWithoutPromotion - $costWithPromotion, 2);
        }
        $total = $total + $costWithPromotion;
        $item=array(
            "idProducto" => (int) $idProducto,
            "descuento" => (double) $discount,
            "cantidad" => (int) $count,
            "precio" => (double) $price
        );
        array_push($detalles, $item);
    }
    $compra->total = $total;
    $compra->createCompra();
    $compra->idCompra = $compra->getId();
    // Loop through the array of Compra
    foreach ($detalles as $item) {
        $compra->idProducto = $item['idProducto'];
        $compra->descuento = $item['descuento'];
        $compra->cantidad = $item['cantidad'];
        $compra->precio = $item['precio'];
        $compra->createCompraXProducto();
    }
    http_response_code(200);
    echo json_encode(true);
}
else{
    //Seteamos estado
    http_response_code(400);
    //Se requieren datos
    echo json_encode(array("message" => "Error Data"));
}
?>