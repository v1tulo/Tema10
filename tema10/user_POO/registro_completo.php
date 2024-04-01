<?php
session_start(); // Inicie a sessão

include 'Usuario.php'; // Inclua o arquivo com a classe Usuario
include 'Database.php'; // Inclua o arquivo com a classe Database para gerenciar a conexão com o banco de dados

$celular = $_SESSION['celular'] ?? ''; // Defina a variável $celular

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Recebendo os dados do formulário
    $nome = $_POST['nome'];
    $cep = $_POST['cep'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $senha = $_POST['senha'];

    // Remover todos os caracteres não numéricos do número de celular
    $celular = preg_replace('/\D/', '', $celular);

    // Criação da instância da classe Database para gerenciar a conexão com o banco de dados
    $db = new Database("localhost", 7306, "root", "", "banco_de_dados");
    $conn = $db->getConnection();

    // Criação da instância da classe Usuario para gerenciar os usuários
    $usuarioManager = new Usuario($conn);

    // Realizar o cadastro completo
    $cadastroSucesso = $usuarioManager->cadastrarUsuario($celular, $nome, $cep, $numero, $complemento, $senha);
    
    if ($cadastroSucesso) {
        // Cadastro realizado com sucesso, redirecionar para página de sucesso
        header("Location: cadastro_sucesso.php");
        exit();
    } else {
        // Erro ao cadastrar o usuário
        echo "Erro ao cadastrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Completo</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adicione o seu arquivo CSS -->
</head>
<body>

<div class="container">
    <h2>Registro Completo</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="celular" placeholder="Número de Celular" value="<?php echo htmlspecialchars($celular); ?>" readonly required>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="cep" placeholder="CEP" required>
        <input type="text" name="numero" placeholder="Número" required>
        <input type="text" name="complemento" placeholder="Complemento">
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit" name="submit">Registrar</button>
    </form>
</div>

</body>
</html>
