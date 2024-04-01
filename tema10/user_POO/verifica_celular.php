<?php
session_start();

include 'Usuario.php';
include 'Database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['celular'])) {
    $celular = $_POST['celular'];
    $celular = preg_replace('/\D/', '', $celular); // Remove todos os caracteres não numéricos

    $db = new Database("localhost", 7306, "root", "", "banco_de_dados");
    $conn = $db->getConnection();

    $usuarioManager = new Usuario($conn);
    $usuario = $usuarioManager->verificarUsuarioExistente($celular);

    if ($usuario) {
        // Celular encontrado, direcionar para completar.php
        $_SESSION['celular'] = $celular; // Armazenar o celular na sessão
        header("Location: completar.php");
        exit();
    } else {
        // Celular não encontrado, redirecionar para registro_completo.php
        $_SESSION['celular'] = $celular; // Armazenar o celular na sessão
        header("Location: registro_completo.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificar Celular</title>
<link rel="stylesheet" href="styles.css"> <!-- Adicione o seu arquivo CSS -->
</head>
<body>

<div class="container">
    <h2>Verificar Celular</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="celularForm">
        <input type="text" id="celular" name="celular" placeholder="Número de Celular" required>
        <button type="submit">Verificar</button>
    </form>
</div>

<script>
// Função para formatar o número de celular enquanto o usuário digita
document.getElementById('celular').addEventListener('input', function(event) {
    // Obtém o valor atual do campo de entrada
    let celular = event.target.value;

    // Remove todos os caracteres não numéricos do número de celular
    celular = celular.replace(/\D/g, '');

    // Formata o número de celular com parênteses e traços
    if (celular.length > 2 && celular.length <= 6) {
        celular = '(' + celular.substring(0, 2) + ') ' + celular.substring(2);
    } else if (celular.length > 6 && celular.length <= 10) {
        celular = '(' + celular.substring(0, 2) + ') ' + celular.substring(2, 6) + '-' + celular.substring(6);
    } else if (celular.length > 10) {
        celular = '(' + celular.substring(0, 2) + ') ' + celular.substring(2, 7) + '-' + celular.substring(7, 11);
    }

    // Atualiza o valor do campo de entrada com o número de celular formatado
    event.target.value = celular;
});
</script>

</body>
</html>
