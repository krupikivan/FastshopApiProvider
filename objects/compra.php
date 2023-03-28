<?php

class Compra{
 
    // database connection and table name
    private $conn;
    private $table_name = "compras";
    private $table_name_2 = "comprasxproducto";
 
    // object properties
    public $fechaCompra;
    public $idCliente;
    public $total;
    public $cantidadTotal;
    
    // Detalle
    public $idCompra;
    public $idProducto;
    public $cantidad;
    public $descuento;
    public $precio;

    public $idsearch;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read compra by cliente
    function read(){

    // select all query
    $query = "SELECT IdCompra, IdCliente, fechaCompra, total, cantidad
                FROM " . $this->table_name . " 
                WHERE IdCliente = ".$this->idsearch."
                ORDER BY fechaCompra asc";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
    }

    function createCompra(){
        //Insertamos query
        $query = "INSERT INTO
        " . $this->table_name . "
        (`IdCompra`, `fechaCompra`, `total`, `cantidad`, `IdCliente`) VALUES
        (NULL, curdate(), ".$this->total.", ".$this->cantidadTotal.", '".$this->idCliente."')";
    
        //Preparamos la query
        $stmt = $this->conn->prepare($query);
    
        //Ejecutamos el script y corroboramos si la query esta OK
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }

    function createCompraXProducto(){

        //Insertamos query
        $query = "INSERT INTO
        " . $this->table_name_2 . "
        (`IdCompraProducto`, `IdCompra`, `IdProducto`, `precio`, `descuento`, `cantidad`) VALUES
        (NULL, '".$this->idCompra."','".$this->idProducto."','".$this->precio."','".$this->descuento."','".$this->cantidad."')";
    
        //Preparamos la query
        $stmt = $this->conn->prepare($query);
    
        $this->idCompra=htmlspecialchars(strip_tags($this->idCompra));
        $this->idProducto=htmlspecialchars(strip_tags($this->idProducto));
    
        //Ejecutamos el script y corroboramos si la query esta OK
        if($stmt->execute()){
    
            return true;
        }
    
        return false;
    }

    function getId(){
        // select all query
        $query = "SELECT LAST_INSERT_ID() as 'IdCompra' FROM " . $this->table_name . "";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
    
        //Creamos vector
        $list_arr=array();
        //Extraemos el resultado
        extract($stmt->fetch(PDO::FETCH_ASSOC));
        //Lo metemos en un vector nuevo
        $list_item=array(
            //Tomamos el dato y lo guardamos en la variable
            "IdCompra" => $IdCompra
        );
        array_push($list_arr, $list_item);
     
        return $IdCompra;
    }

    function readCompra($id){

        // select all query
        $query = "SELECT c.total as 'totalCompra', p.Descripcion, cc.descuento, cc.precio, cc.cantidad FROM " . $this->table_name_2 . " cc 
        JOIN productos p ON p.IdProducto = cc.IdProducto 
        JOIN " . $this->table_name . " c ON c.IdCompra = cc.IdCompra 
        WHERE cc.IdCompra = '".$id."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

}
?>