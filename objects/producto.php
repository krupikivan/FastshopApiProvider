<?php

class Producto{
 
    // database connection and table name
    private $conn;
    private $table_name = "productos";
 
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
            $query = "SELECT p.idProducto, p.codigo, p.descripcion, c.descripcion as 'categoria', 
            m.nombre as 'marca', p.precio 
            FROM " . $this->table_name . " p 
            JOIN categorias c ON p.idCategoriaFK = c.idCategoria  
            JOIN marcas m on m.idMarca = p.idMarcaFK WHERE c.idCategoria = '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        function readProductList(){

            // select all query
            $query = "SELECT p.idProducto, p.codigo, p.descripcion, c.descripcion as 'categoria', m.nombre as 'marca', p.precio FROM
    " . $this->table_name . " p
    JOIN categorias c on c.idCategoria = p.idCategoriaFK 
    JOIN marcas m on m.idMarca = p.idMarcaFK";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

        //Le marcamos en 1 la casilla creado de la tabla listado
function getProductScanned(){

    //Insertamos query
	$query = "SELECT p.idProducto, p.descripcion, c.descripcion as 'categoria', m.nombre as 'marca', pp.precio FROM
    " . $this->table_name . " p
    JOIN categorias c on c.idCategoria = p.idCategoriaFK 
    JOIN marcas m on m.idMarca = p.idMarcaFK
    WHERE codigo like '".$this->codigo."'";
    
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->idProducto=htmlspecialchars(strip_tags($this->idProducto));
    $this->descripcion=htmlspecialchars(strip_tags($this->descripcion));
    $this->categoria=htmlspecialchars(strip_tags($this->categoria));
    $this->marca=htmlspecialchars(strip_tags($this->marca));
    $this->precio=htmlspecialchars(strip_tags($this->precio));

    // bind the values
    $stmt->bindParam(':idProducto', $this->idProducto);
    $stmt->bindParam(':descripcion', $this->descripcion);
    $stmt->bindParam(':categoria', $this->categoria);
    $stmt->bindParam(':marca', $this->marca);
    $stmt->bindParam(':precio', $this->precio);

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
        $this->descripcion = $row['descripcion'];
        $this->categoria = $row['categoria'];
        $this->marca = $row['marca'];
        $this->precio = $row['precio'];


        // True porque existe en la DB
        return $stmt;
    }
 
    // False porque no existe en la DB
    return $stmt;
}

}
?>