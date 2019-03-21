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

//Creamos el listado de compras en la tabla listado
function createName(){
    
    //Existe el listado?
    if($this->listExist()){
        return false;
    }

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idListado`, `fechaCreacion`, `fechaCobro`, `fechaCompra`, `nombre`) VALUES
    (NULL, '".$this->fechaCreacion."', '".$this->fechaCobro."', '".$this->fechaCompra."', '".$this->nombre."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->fechaCreacion=htmlspecialchars(strip_tags($this->fechaCreacion));
    $this->fechaCobro=htmlspecialchars(strip_tags($this->fechaCobro));
    $this->fechaCompra=htmlspecialchars(strip_tags($this->fechaCompra));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':fechaCreacion', $this->fechaCreacion);
    $stmt->bindParam(':fechaCobro', $fechaCobro);
    $stmt->bindParam(':fechaCompra', $this->fechaCompra);
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){
    
        $stmt->bindParam(':idListado', $this->idListado);
    
        return true;
    }

    return false;
    }
}

function getId(){
    return $idListado;
}

    //Existe el nombre de la lista?
function listExist(){
 
    //Chequear si existe el usuario
    $query = "SELECT *
            FROM " . $this->table_name . "
            WHERE nombre = '".$this->nombre."'";
 
    //Preparamos query
    $stmt = $this->conn->prepare( $query );
 
    //Sanitizar
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));
 
    //Enlazamos valores
    $stmt->bindParam(1, $this->nombre);
 
    //Ejecutamos query
    $stmt->execute();
 
    //Numero de filas
    $num = $stmt->rowCount();
 
    // Si existe asignamos valores al objeto (lo podemos usar para manejo de sesiones)
    if($num>0){
        
        // False porque existe en la DB
        return false;
    }
 
    // True porque no existe en la DB
    return true;
}

//Creamos el listado de compras en la tabla listadoxsubcategorias
function createCategory($id, $filas){

    //Array to keep track of errors
	$my_errors = array();

    for($count = 0; $count < sizeof($filas); $count++)
    {
        if(!empty($filas[$count]))
        {
            $student = mysql_real_escape_string($id[$count]);
            $mark = mysql_real_escape_string($score[$count]);
            
            $query = "INSERT INTO try (id, score) VALUES('".$student."',$mark)";
            if(!mysql_query($query))
            {
                $my_errors[] = $id[$count];
            }
        }
    }

    //Insertamos query
	$query = "INSERT INTO listadoxsubcategorias
    (`idListadoxsubcate`, `idListado`, `idCat`) VALUES
    (NULL, '".$this->fechaCreacion."', '".$this->fechaCobro."', '".$this->fechaCompra."', '".$this->nombre."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->fechaCreacion=htmlspecialchars(strip_tags($this->fechaCreacion));
    $this->fechaCobro=htmlspecialchars(strip_tags($this->fechaCobro));
    $this->fechaCompra=htmlspecialchars(strip_tags($this->fechaCompra));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':fechaCreacion', $this->fechaCreacion);
    $stmt->bindParam(':fechaCobro', $fechaCobro);
    $stmt->bindParam(':fechaCompra', $this->fechaCompra);
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){
    return true;
    }

    return false;
    }
}

}
?>