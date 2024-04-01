<?php
include 'includes/Produto.php';

// Configuração do banco de dados
$servername = "localhost";
$port = 7306;
$username = "root";
$password = "";
$dbname = "banco_de_dados";

try {
    // Conexão com o banco de dados
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recupera todos os produtos do banco de dados
    $produtos = Produto::getAllProducts($conn);
} catch(PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Produtos</h1>
        <div class="produtos">
            <?php foreach ($produtos as $produto): ?>
                <div class="produto">
                    <img src="uploads/<?php echo $produto->getImagem(); ?>" alt="<?php echo $produto->getProduto(); ?>">
                    <h2><?php echo $produto->getProduto(); ?></h2>
                    <p>R$ <?php echo number_format($produto->getValor(), 2, ',', '.'); ?></p>
					 
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
