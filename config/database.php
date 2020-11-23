<?php
class Database{


    //Especificamos nuestra Base de Datos y conexion LOCAL
    //  private $host = "localhost";
    //  private $db_name = "fastshop";
    //  private $username = "root";
    //  private $password = "root";

    //BD Herni
    //private $host = "localhost";
    //private $db_name = "fastshop";
    //private $username = "root";
    //private $password = "root"; //no mirar

    //Especificamos nuestra Base de Datos y conexion WEB
    // private $host = "https://fastshop2020.000webhostapp.com";
    // private $host = "35.202.59.132";
    // private $host = "fastshop-296500:us-central1:fastshop";
    // private $host = "localhost";
    // private $host = ":/cloudsql/projectID:fastshop-296500:us-central1:fastshop";
    private $host;
    private $db_name;
    private $username;
    private $password;
    // private $password = 'root5562AAssss&&';

    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST');
        $this->db_name = getenv('MYSQL_DB');
        $this->username = getenv('MYSQL_USER');
        $this->password = getenv('MYSQL_PASS');
    }


    public $conn;

    //Obtenemos la conexion a la DB
    public function getConnection(){

        $this->conn = null;
        try{
            // $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,
            $this->conn = new PDO($this->host . ";dbname=" . $this->db_name,
            $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Error de conexion: " . $exception->getMessage();
        }
        return $this->conn;
    } 

    // public function getConnection(){

    //     $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name) or die (mysqli_connect_error());
    //     if($this->conn){
    //         print('Conexion exitosa');
    //     }
    //     return $this->conn;
    // } 
}
?>