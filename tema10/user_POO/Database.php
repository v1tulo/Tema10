<?php

class Database {
    private $conn;

    public function __construct($servername, $port, $username, $password, $dbname) {
        try {
            $this->conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}

?>
