<?php

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para verificar se o usuário existe pelo celular
    public function verificarUsuarioExistente($celular) {
        $sql = "SELECT * FROM users_app WHERE celular = :celular";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':celular', $celular);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para cadastrar um novo usuário
    public function cadastrarUsuario($celular, $nome, $cep, $numero, $complemento, $senha) {
        $sql = "INSERT INTO users_app (celular, nome, cep, numero, complemento, senha) VALUES (:celular, :nome, :cep, :numero, :complemento, :senha)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT)); // Criptografando a senha
        return $stmt->execute();
    }

    // Método para atualizar os dados do usuário
    public function atualizarUsuario($celular, $nome, $cep, $numero, $complemento) {
        $sql = "UPDATE users_app SET nome = :nome, cep = :cep, numero = :numero, complemento = :complemento WHERE celular = :celular";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':celular', $celular);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        return $stmt->execute();
    }
}
