<?php
class User{

    //Conexion a DB y tabla
    private $conn;
    //Insertamos el nombre de la tabla
    private $table_name = "user";
    //Propiedades del objeto
    public $id;
    public $username;
    public $password;
    public $created;

    //Constructor con $db como la conexion de DB
    public function __construct($db){
        $this->conn = $db;
        
    }
    
//Creamos el usuario
function create(){
    
    //Existe el usuario?
    if($this->userExist()){
        return false;
    }

    //Hasheamos la password
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idUsuario`, `username`, `password`, `created`)
VALUES
    (NULL, '".$this->username."', '".$password_hash."', '".$this->created."')";

//Preparamos la query
$stmt = $this->conn->prepare($query);

// sanitize
$this->username=htmlspecialchars(strip_tags($this->username));
$this->password=htmlspecialchars(strip_tags($this->password));
$this->created=htmlspecialchars(strip_tags($this->created));

// bind the values
$stmt->bindParam(':username', $this->username);
$stmt->bindParam(':password', $password_hash);
$stmt->bindParam(':created', $this->created);


//Ejecutamos el script y corroboramos si la query esta OK
if($stmt->execute()){
return true;
}

return false;
}

//Existe el usuario??
function userExist(){
 
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
        $this->idUsuario = $row['idUsuario'];
        $this->username = $row['username'];
        $this->password = $row['password'];
        
        // True porque existe en la DB
        return true;
    }
 
    // False porque no existe en la DB
    return false;
}

}
?>