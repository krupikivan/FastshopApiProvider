<?php

class Listado{
 
    // database connection and table name
    private $conn;
    private $table_name = "listados";
 
    // object properties
    public $idListado;
    public $fechaCreacion;
    public $fechaCobro;
    public $fechaCompra;
    public $cliente;
    public $nombre;
    public $producto;
    public $cantidad;
    public $username;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read data
    function readData(){

    // select all query
    $query = "SELECT li.idListado, li.nombre, p.descripcion AS 'producto', lp.cant AS 'cantidad', c.username as 'cliente' 
    FROM " . $this->table_name . " li 
    JOIN listadoxproductos lp ON lp.idListado = li.idListado
    JOIN listadoxconsumidores lc ON lc.idListado = li.idListado
    JOIN productos p ON p.idProducto = lp.idProducto
    JOIN clientes c ON c.idCliente = lc.idCliente
    WHERE c.username like 'admin'
    ORDER BY li.nombre DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

    // read list name, client filtering
    function readName($username){

        // select all query
        $query = "SELECT li.idListado, li.nombre FROM " . $this->table_name . " li 
        JOIN listadoxconsumidores lc ON lc.idListado = li.idListado 
        JOIN clientes c ON c.idCliente = lc.idCliente 
        WHERE c.username = '".$username."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

        // read list products name
        function readProductsList($id){

            // select all query
            $query = "SELECT p.descripcion FROM " . $this->table_name . " li 
            JOIN listadoxproductos lp ON lp.idListado = li.idListado 
            JOIN productos p ON p.idProducto = lp.idProducto 
            WHERE li.idListado = '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

}
?>