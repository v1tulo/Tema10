<?php
session_start();

include 'Usuario.php';
include 'Database.php';

// Verificar se o celular está definido na sessão
if (!isset($_SESSION['celular'])) {
    header("Location: verifica_celular.php");
    exit();
}

// Obter o celular da sessão
$celular = $_SESSION['celular'];

// Instanciar a classe Database para gerenciar a conexão com o banco de dados
$db = new Database("localhost", 7306, "root", "", "banco_de_dados");
$conn = $db->getConnection();

// Instanciar a classe Usuario para gerenciar os usuários
$usuarioManager = new Usuario($conn);

// Obter os dados do usuário
$usuario = $usuarioManager->verificarUsuarioExistente($celular);

// Verificar se o formulário foi enviado e a senha foi fornecida
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['senha'])) {
    $senha = $_POST['senha'];

    // Verificar se a senha fornecida corresponde à senha do usuário
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Senha correta, realizar as ações necessárias (por exemplo, atualizar os dados do usuário)
        $nome = $_POST['nome'];
        $cep = $_POST['cep'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        
        $usuarioManager->atualizarUsuario($celular, $nome, $cep, $numero, $complemento);
        
        // Redirecionar para a página de sucesso e limpar a sessão
        unset($_SESSION['celular']);
        header("Location: cadastro_sucesso.php");
        exit();
    } else {
        // Senha incorreta, redirecionar de volta para completar.php com mensagem de erro
        header("Location: completar.php?error=senha_incorreta");
        exit();
    }
}

// Se o formulário não foi enviado ou a senha não foi fornecida, exibir o formulário para completar o cadastro
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Cadastro</title>
    <link rel="stylesheet" href="styles.css"> <!-- Adicione o seu arquivo CSS -->
</head>
<body>

<div class="container">
    <h2>Completar Cadastro</h2>
    <?php if (isset($_GET['error']) && $_GET['error'] === "senha_incorreta"): ?>
        <p class="error">Senha incorreta. Tente novamente.</p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="nome" placeholder="Nome" value="<?php echo isset($usuario['nome']) ? $usuario['nome'] : ''; ?>" required>
        <input type="text" name="cep" placeholder="CEP" value="<?php echo isset($usuario['cep']) ? $usuario['cep'] : ''; ?>" required>
        <input type="text" name="numero" placeholder="Número" value="<?php echo isset($usuario['numero']) ? $usuario['numero'] : ''; ?>" required>
        <input type="text" name="complemento" placeholder="Complemento" value="<?php echo isset($usuario['complemento']) ? $usuario['complemento'] : ''; ?>">
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Enviar</button>
    </form>
</div>

</body>
</html>
