<?php

class Produto {
    private $id;
    private $produto;
    private $valor;
    private $imagem;

    public function __construct($id, $produto, $valor, $imagem) {
        $this->id = $id;
        $this->produto = $produto;
        $this->valor = $valor;
        $this->imagem = $imagem;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getproduto() {
        return $this->produto;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getImagem() {
        return $this->imagem;
    }

    // Método estático para recuperar todos os produtos do banco de dados
    public static function getAllProducts($conn) {
        $sql = "SELECT * FROM produtos";
        $stmt = $conn->query($sql);
        $produtos = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = new Produto($row['id'], $row['produto'], $row['valor'], $row['imagem']);
        }

        return $produtos;
    }
}

?>
