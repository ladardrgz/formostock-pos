<?php
class Database {
    private $host = "localhost:3307";
    private $db_name = "bdformostock";
    private $username = "root";
    private $password = "";
    public $conn;

    // Método para obtener la conexión
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
