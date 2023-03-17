<?php

class Producto{
 
    // database connection and table name
    private $conn;
    private $table_name = "productos";
    private $table_name_2 = "promocionxproducto";
    private $table_name_3 = "promocion";
 
    // object properties
    public $idProducto;
    public $codigo;
    public $descripcion;
    public $categoria;
    public $marca;
    public $precio;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

        // read list products name
        function readProductsCateg($id){

            // select all query
            $query = "SELECT p.idProducto, p.Codigo, p.Descripcion, c.Descripcion as 'categoria', 
            m.Nombre as 'marca', p.Precio 
            FROM " . $this->table_name . " p 
            JOIN Categorias c ON p.IdCategoriaFK = c.idCategoria  
            JOIN Marcas m on m.idMarca = p.IdMarcaFK WHERE c.idCategoria = '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        function readProductList(){

            // select all query
            $query = "SELECT p.idProducto, p.Codigo, p.Descripcion, c.Descripcion as 'categoria', m.Nombre as 'marca', p.Precio FROM
    " . $this->table_name . " p
    JOIN Categorias c on c.idCategoria = p.IdCategoriaFK 
    JOIN Marcas m on m.idMarca = p.IdMarcaFK";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        //Le marcamos en 1 la casilla creado de la tabla listado
function getProductScanned(){

    //Insertamos query
	$query = "SELECT p.idProducto, p.Descripcion, c.Descripcion as 'categoria', m.Nombre as 'marca', p.Precio FROM
    " . $this->table_name . " p
    JOIN Categorias c on c.idCategoria = p.IdCategoriaFK 
    JOIN Marcas m on m.idMarca = p.IdMarcaFK
    WHERE Codigo like '".$this->codigo."'";
    
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->idProducto=htmlspecialchars(strip_tags($this->idProducto));
    $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
    $this->categoria=htmlspecialchars(strip_tags($this->categoria));
    $this->marca=htmlspecialchars(strip_tags($this->marca));
    $this->precio=htmlspecialchars(strip_tags($this->precio));

    //Ejecutamos el script y corroboramos si la query esta OK
    $stmt->execute();

    //Numero de filas
    $num = $stmt->rowCount();

    // Si existe asignamos valores al objeto (lo podemos usar para manejo de sesiones)
    if($num>0){

        // Traemos valores
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        //Asignamos valores
        $this->idProducto = $row['idProducto'];
        $this->descripcion = $row['Descripcion'];
        $this->categoria = $row['categoria'];
        $this->marca = $row['marca'];
        $this->precio = $row['Precio'];


        // True porque existe en la DB
        return $stmt;
    }
 
    // False porque no existe en la DB
    return $stmt;
}


function getTotalPrice(array $productIds){

    //Insertamos query
	$query = "SELECT sum(Precio) FROM
    " . $this->table_name . "
    WHERE IdProducto in (".implode(',',$productIds).")";
    
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    //Ejecutamos el script y corroboramos si la query esta OK
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_NUM);

    $sum = $result[0];
 
    return (float) $sum;
}

function getProductsCompra(array $productIds){

    // select all query
    $query = "SELECT p.IdProducto, p.Precio, pr.Formula, pp.Stock, pr.CantidadProductos, pr.ProductoAplicado FROM
    " . $this->table_name . " p
    RIGHT OUTER JOIN " . $this->table_name_2 . " pp on p.IdProducto = pp.IdProductoFK 
    RIGHT OUTER JOIN " . $this->table_name_3 . " pr on pr.IdTipoPromocion = pp.IdTipoPromocionFK
    WHERE p.IdProducto in (".implode(',',$productIds).")
    AND pr.ClasePromocion = 'Producto'
    OR pr.ClasePromocion IS NULL
    AND pp.FechaInicio IS NULL
    OR pp.FechaInicio <= NOW() AND pp.FechaFin > NOW()";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    $num = $stmt->rowCount();
 
    $list_arr=array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $list_item=array(
            "IdProducto" => (int)$IdProducto,
            "Precio" => (float)$Precio,
            "Formula" => (float)$Formula,
            "CantidadProductos" => (int)$CantidadProductos,
            "ProductoAplicado" => (int)$ProductoAplicado,
            "Stock" => (int)$Stock
        );
        array_push($list_arr, $list_item);
    }

    return $list_arr;
}


}
?>