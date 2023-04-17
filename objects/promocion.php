<?php

class Promocion{
 
    // database connection and table name
    private $conn;
    private $table_name = "PromocionXProducto";
 
    // object properties
    public $idPromocion;
    public $fechaFin;
    public $fechaInicio;
    public $producto;
    public $CantidadProductos;
    public $ProductoAplicado;
    public $Formula;
    public $promocion;
    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read products
    function read(){

    // select all query
    $query = "SELECT IdPromocion, CantidadProductos, Formula, ProductoAplicado, Prioridad, DATE_FORMAT(pp.FechaFin,'%d-%m-%y') as 'FechaFin', DATE_FORMAT(pp.fechaInicio,'%d-%m-%y') as 'FechaInicio', pr.Descripcion AS 'producto', c.Descripcion AS 'categoria', p.Descripcion AS 'promocion', pr.IdProducto, c.IdCategoria 
    FROM Promocion p    
    JOIN PromocionXProducto pp ON p.IdTipoPromocion = pp.IdTipoPromocionFK 
    LEFT JOIN Productos pr ON pr.IdProducto = pp.IdProductoFK
    LEFT JOIN Categorias c ON c.IdCategoria = pp.IdCategoriaFK
    WHERE pp.FechaInicio <= NOW() AND pp.FechaFin > NOW()
    ORDER BY
    IdPromocion ASC";
    // pp.FechaFin DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

function readStream($queryParam){

    // select all query
    $query = "SELECT pp.idPromocion, pp.FechaFin, pp.FechaInicio, pr.Descripcion AS 'producto', p.Descripcion AS 'promocion' 
                FROM " . $this->table_name . " pp 
                JOIN Promocion p ON p.idTipoPromocion = pp.idTipoPromocion 
                JOIN Productos pr ON pr.idProducto = pp.idProducto 
                WHERE pp.FechaInicio <= NOW() AND pp.FechaFin > NOW() AND p.Descripcion LIKE '%".$queryParam."%'
                ORDER BY
                pp.FechaFin DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>