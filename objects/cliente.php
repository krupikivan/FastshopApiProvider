<?php

class Cliente{

    //Conexion a DB y tabla
    private $conn;
    //Insertamos el nombre de la tabla
    private $table_name = "clientes";

    public $idCliente;
    public $username;
    public $apellido;
    public $email;
    public $nombre;
    public $password;
    public $nroDoc;
    public $idTipoDocFK;

    //Constructor con $db como la conexion de DB
    public function __construct($db){
        $this->conn = $db;
        
    }

    //Creamos el cliente que va a utilizar la APP
function create(){
    
    //Existe el cliente?
    if($this->clientExist()){
        return false;
    }

    //Hasheamos la password
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);


    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idCliente`, `username`, `apellido`, `email`, `nombre`, `password`, `nroDoc`, `idTipoDocFK`)
VALUES
    (NULL, '".$this->username."', '".$this->apellido."', '".$this->email."', '".$this->nombre."', '".$password_hash."', '".$this->nroDoc."', 1)";
//Preparamos la query
$stmt = $this->conn->prepare($query);

// sanitize
$this->username=htmlspecialchars(strip_tags($this->username));
$this->apellido=htmlspecialchars(strip_tags($this->apellido));
$this->email=htmlspecialchars(strip_tags($this->email));
$this->nombre=htmlspecialchars(strip_tags($this->nombre));
$this->password=htmlspecialchars(strip_tags($this->password));
$this->nroDoc=htmlspecialchars(strip_tags($this->nroDoc));

// bind the values
$stmt->bindParam(':username', $this->username);
$stmt->bindParam(':apellido', $this->apellido);
$stmt->bindParam(':email', $this->email);
$stmt->bindParam(':nombre', $this->nombre);
$stmt->bindParam(':password', $password_hash);
$stmt->bindParam(':nroDoc', $this->nroDoc);


//Ejecutamos el script y corroboramos si la query esta OK
if($stmt->execute()){
return true;
}

return false;
}

//Existe el usuario??
function clientExist(){
 
    //Chequear si existe el usuario
    $query = "SELECT *
            FROM " . $this->table_name . "
            WHERE username = '".$this->username."'";
 
    //Preparamos query
    $stmt = $this->conn->prepare( $query );
 
    //Sanitizar
    $this->username=htmlspecialchars(strip_tags($this->username));
 
    //Enlazamos valores
    $stmt->bindParam(1, $this->username);
 
    //Ejecutamos query
    $stmt->execute();
 
    //Numero de filas
    $num = $stmt->rowCount();

    // Si existe asignamos valores al objeto (lo podemos usar para manejo de sesiones)
    if($num>0){

        // Traemos valores
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        //Asignamos valores
        $this->idCliente = $row['idCliente'];
        $this->username = $row['username'];
        $this->apellido = $row['apellido'];
        $this->email = $row['email'];
        $this->nombre = $row['nombre'];
        $this->password = $row['password'];
        $this->nroDoc = $row['nroDoc'];


        // True porque existe en la DB
        return true;
    }
 
    // False porque no existe en la DB
    return false;
}

function getUsername(){
    return $this->username;
}

function getId(){
    return $this->idCliente;
}

//Login Cliente
function login(){
    //Seleccionar todas las query
    $query = "SELECT
                * 
            FROM
                " . $this->table_name . "
            WHERE
            username='".$this->username."' AND password='".$this->password."'";
    //Preparar query statement
    $stmt = $this->conn->prepare($query);
    //Ejecutar query
    $stmt->execute();
    return $stmt;
}

}


?>